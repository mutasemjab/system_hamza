<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\SocialContent;
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

        return view('admin.social.index', compact('board', 'types'));
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

        // Only one "next" per type allowed
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

        // Promote the next in queue for this type
        $nextInQueue = SocialContent::where('content_type', $social->content_type)
            ->where('status', 'pending')
            ->orderBy('sort_order')
            ->first();

        if ($nextInQueue) {
            $nextInQueue->update(['status' => 'next']);
        }

        return redirect()->route('social.index')
            ->with('success', 'تم تأكيد نشر المحتوى وترقية اللاعب التالي');
    }

    public function destroy(SocialContent $social)
    {
        $social->delete();
        return redirect()->route('social.index')
            ->with('success', 'تم حذف المحتوى');
    }
}
