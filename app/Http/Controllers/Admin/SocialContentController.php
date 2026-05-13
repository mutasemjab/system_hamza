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
        return redirect()->route('social.schedule');
    }

    /* ── Main Schedule Page ── */

    public function scheduleForm()
    {
        $players = Player::orderBy('full_name')->get();

        $scheduled = SocialContent::with('player')
            ->orderByDesc('scheduled_date')
            ->orderByDesc('id')
            ->paginate(50);

        return view('admin.social.schedule', compact('players', 'scheduled'));
    }

    /* ── Bulk Schedule Generator ── */

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
            'notes'       => 'nullable|string|max:500',
        ]);

        $playerIds   = $request->player_ids;
        $days        = array_map('intval', $request->days);
        $description = trim($request->description);
        $notes       = $request->notes ? trim($request->notes) : null;
        $start       = Carbon::parse($request->start_date)->startOfDay();
        $end         = Carbon::parse($request->end_date)->startOfDay();

        $rows        = [];
        $playerIndex = 0;
        $cursor      = $start->copy();

        while ($cursor->lte($end)) {
            if (in_array($cursor->dayOfWeek, $days)) {
                $rows[] = [
                    'player_id'          => $playerIds[$playerIndex % count($playerIds)],
                    'custom_description' => $description,
                    'scheduled_date'     => $cursor->toDateString(),
                    'status'             => 'pending',
                    'notes'              => $notes,
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

    /* ── Single Entry Add ── */

    public function store(Request $request)
    {
        $request->validate([
            'player_id'          => 'required|exists:players,id',
            'custom_description' => 'required|string|max:255',
            'scheduled_date'     => 'required|date',
            'status'             => 'required|in:pending,published',
            'notes'              => 'nullable|string|max:500',
        ]);

        SocialContent::create([
            'player_id'          => $request->player_id,
            'custom_description' => $request->custom_description,
            'scheduled_date'     => $request->scheduled_date,
            'status'             => $request->status,
            'notes'              => $request->notes,
            'published_at'       => $request->status === 'published' ? now()->toDateString() : null,
        ]);

        return redirect()->route('social.schedule')
            ->with('success', 'تم إضافة الجلسة بنجاح');
    }

    /* ── Edit ── */

    public function update(Request $request, SocialContent $social)
    {
        $request->validate([
            'player_id'          => 'required|exists:players,id',
            'custom_description' => 'required|string|max:255',
            'scheduled_date'     => 'required|date',
            'status'             => 'required|in:pending,published',
            'notes'              => 'nullable|string|max:500',
        ]);

        $social->update([
            'player_id'          => $request->player_id,
            'custom_description' => $request->custom_description,
            'scheduled_date'     => $request->scheduled_date,
            'status'             => $request->status,
            'notes'              => $request->notes,
            'published_at'       => $request->status === 'published'
                ? ($social->published_at?->toDateString() ?? now()->toDateString())
                : null,
        ]);

        return redirect()->route('social.schedule')
            ->with('success', 'تم تحديث الجلسة');
    }

    /* ── Mark Published ── */

    public function markPublished(SocialContent $social)
    {
        $social->update([
            'status'       => 'published',
            'published_at' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'تم تأكيد النشر');
    }

    /* ── Delete ── */

    public function destroy(SocialContent $social)
    {
        $social->delete();
        return redirect()->back()->with('success', 'تم حذف الجلسة');
    }
}
