<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\SocialQueue;
use App\Models\SocialQueueEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SocialQueueController extends Controller
{
    private static array $palette = [
        '#0c3c2c', '#3b82f6', '#8b5cf6', '#ec4899',
        '#f59e0b', '#ef4444', '#06b6d4', '#1a7a55',
        '#f97316', '#14b8a6',
    ];

    public function index()
    {
        $queues  = SocialQueue::with(['pendingEntries.player', 'publishedEntries.player'])
            ->orderBy('sort_order')
            ->get();
        $players = Player::orderBy('full_name')->get();

        return view('admin.social.queues', compact('queues', 'players'));
    }

    public function storeQueue(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $count = SocialQueue::count();
        $color = self::$palette[$count % count(self::$palette)];

        SocialQueue::create([
            'name'       => $request->name,
            'color'      => $color,
            'sort_order' => $count,
        ]);

        return back()->with('success', 'تم إنشاء القائمة.');
    }

    public function destroyQueue(SocialQueue $queue)
    {
        $queue->delete();
        return back()->with('success', 'تم حذف القائمة.');
    }

    public function addEntries(Request $request, SocialQueue $queue)
    {
        $request->validate(['player_ids' => 'required|array', 'player_ids.*' => 'exists:players,id']);

        $base = $queue->entries()->max('sort_order') ?? -1;

        foreach ($request->player_ids as $i => $pid) {
            // skip if player already in this queue (any status)
            if ($queue->entries()->where('player_id', $pid)->exists()) {
                continue;
            }
            SocialQueueEntry::create([
                'queue_id'   => $queue->id,
                'player_id'  => $pid,
                'sort_order' => $base + 1 + $i,
                'status'     => 'pending',
            ]);
        }

        return back()->with('success', 'تم إضافة الطلاب.');
    }

    public function destroyEntry(SocialQueueEntry $entry)
    {
        $entry->delete();
        return back()->with('success', 'تم حذف الطالب من القائمة.');
    }

    // AJAX: toggle a single entry pending ↔ published
    public function toggleEntry(SocialQueueEntry $entry)
    {
        if ($entry->status === 'pending') {
            $entry->update(['status' => 'published', 'published_at' => Carbon::today()]);
        } else {
            $entry->update(['status' => 'pending', 'published_at' => null]);
        }

        return response()->json([
            'status'       => $entry->status,
            'published_at' => $entry->published_at?->format('Y-m-d'),
        ]);
    }

    // AJAX: mark ALL pending entries in a queue as published
    public function markAllDone(SocialQueue $queue)
    {
        $queue->pendingEntries()->update([
            'status'       => 'published',
            'published_at' => Carbon::today()->toDateString(),
        ]);

        return response()->json(['ok' => true]);
    }

    // Reset: move all published entries back to pending for a new round
    public function resetQueue(SocialQueue $queue)
    {
        $queue->publishedEntries()->update([
            'status'       => 'pending',
            'published_at' => null,
        ]);

        return back()->with('success', 'تم بدء جولة جديدة.');
    }
}
