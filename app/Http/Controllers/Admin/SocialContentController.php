<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\SocialContent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SocialContentController extends Controller
{
    public function index()
    {
        $types = SocialContent::$types;
        $board = [];

        foreach ($types as $type => $meta) {
            $board[$type] = [
                'meta'      => $meta,
                'current'   => SocialContent::with('player')->where('content_type', $type)->where('status', 'next')->first(),
                'queue'     => SocialContent::with('player')->where('content_type', $type)->where('status', 'pending')->orderBy('sort_order')->get(),
                'published' => SocialContent::with('player')->where('content_type', $type)->where('status', 'published')->latest('published_at')->limit(5)->get(),
            ];
        }

        // Upcoming scheduled posts (custom, date-based)
        $scheduledUpcoming = SocialContent::scheduled()
            ->with('player')
            ->where('status', '!=', 'published')
            ->orderBy('scheduled_date')
            ->limit(30)
            ->get();

        return view('admin.social.index', compact('board', 'types', 'scheduledUpcoming'));
    }

    public function create()
    {
        $players = Player::orderBy('full_name')->get();
        $types   = SocialContent::$types;
        return view('admin.social.create', compact('players', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_id'    => 'required|exists:players,id',
            'content_type' => 'required|in:' . implode(',', array_keys(SocialContent::$types)),
            'status'       => 'required|in:pending,next,published',
            'notes'        => 'nullable|string',
        ]);

        if ($request->status === 'next') {
            SocialContent::where('content_type', $request->content_type)
                ->where('status', 'next')
                ->update(['status' => 'pending']);
        }

        $maxOrder = SocialContent::where('content_type', $request->content_type)->max('sort_order') ?? 0;

        SocialContent::create([
            'player_id'    => $request->player_id,
            'content_type' => $request->content_type,
            'status'       => $request->status,
            'notes'        => $request->notes,
            'sort_order'   => $maxOrder + 1,
            'published_at' => $request->status === 'published' ? now()->toDateString() : null,
        ]);

        return redirect()->route('social.index')
            ->with('success', 'تم إضافة المحتوى بنجاح');
    }

    public function edit(SocialContent $social)
    {
        $players = Player::orderBy('full_name')->get();
        $types   = SocialContent::$types;
        return view('admin.social.edit', compact('social', 'players', 'types'));
    }

    public function update(Request $request, SocialContent $social)
    {
        $request->validate([
            'player_id'    => 'required|exists:players,id',
            'content_type' => 'required|in:' . implode(',', array_keys(SocialContent::$types)),
            'status'       => 'required|in:pending,next,published',
            'notes'        => 'nullable|string',
        ]);

        if ($request->status === 'next') {
            SocialContent::where('content_type', $request->content_type)
                ->where('status', 'next')
                ->where('id', '!=', $social->id)
                ->update(['status' => 'pending']);
        }

        $social->update([
            'player_id'    => $request->player_id,
            'content_type' => $request->content_type,
            'status'       => $request->status,
            'notes'        => $request->notes,
            'published_at' => $request->status === 'published' ? ($social->published_at ?? now()->toDateString()) : null,
        ]);

        return redirect()->route('social.index')
            ->with('success', 'تم تحديث المحتوى');
    }

    public function markPublished(SocialContent $social)
    {
        $social->update([
            'status'       => 'published',
            'published_at' => now()->toDateString(),
        ]);

        // Only promote next in queue for typed content, not scheduled entries
        if ($social->content_type !== null) {
            $next = SocialContent::where('content_type', $social->content_type)
                ->whereNull('scheduled_date')
                ->where('status', 'pending')
                ->orderBy('sort_order')
                ->first();

            if ($next) {
                $next->update(['status' => 'next']);
            }
        }

        return redirect()->back()
            ->with('success', 'تم تأكيد نشر المحتوى وترقية اللاعب التالي');
    }

    public function destroy(SocialContent $social)
    {
        $social->delete();
        return redirect()->back()
            ->with('success', 'تم حذف المحتوى');
    }

    /* ── Schedule Generator ── */

    public function scheduleForm()
    {
        $players = Player::orderBy('full_name')->get();

        // Past + upcoming scheduled entries for the list
        $scheduled = SocialContent::scheduled()
            ->with('player')
            ->orderByDesc('scheduled_date')
            ->limit(60)
            ->get();

        return view('admin.social.schedule', compact('players', 'scheduled'));
    }

    public function scheduleGenerate(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'player_ids'  => 'required|array|min:1',
            'player_ids.*'=> 'exists:players,id',
            'days'        => 'required|array|min:1',
            'days.*'      => 'integer|between:0,6',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $playerIds   = $request->player_ids;
        $days        = array_map('intval', $request->days);
        $description = trim($request->description);
        $start       = Carbon::parse($request->start_date)->startOfDay();
        $end         = Carbon::parse($request->end_date)->startOfDay();

        $rows        = [];
        $playerIndex = 0;
        $cursor      = $start->copy();

        while ($cursor->lte($end)) {
            if (in_array($cursor->dayOfWeek, $days)) {
                $rows[] = [
                    'player_id'          => $playerIds[$playerIndex % count($playerIds)],
                    'content_type'       => null,
                    'custom_description' => $description,
                    'scheduled_date'     => $cursor->toDateString(),
                    'status'             => 'pending',
                    'sort_order'         => 0,
                    'notes'              => null,
                    'published_at'       => null,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
                $playerIndex++;
            }
            $cursor->addDay();
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'لا يوجد أي يوم مطابق في النطاق المحدد');
        }

        SocialContent::insert($rows);

        return redirect()->route('social.schedule')
            ->with('success', 'تم إنشاء ' . count($rows) . ' جلسة بنجاح');
    }
}
