<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\SocialContent;
use Illuminate\Http\Request;

class SocialContentController extends Controller
{
    /* ── Main Schedule Page ── */

    public function scheduleForm()
    {
        // Pending first (null published_at), then published ordered oldest → newest (newest at very bottom)
        $grouped = SocialContent::with('player')
            ->orderByRaw("ISNULL(published_at) DESC, published_at ASC, id ASC")
            ->get()
            ->groupBy(fn (SocialContent $e) => $e->custom_description);

        return view('admin.social.schedule', compact('grouped'));
    }

    /* ── Auto-generate group from active players ── */

    public function scheduleGenerate(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'notes'       => 'nullable|string|max:500',
        ]);

        $description = trim($request->description);
        $notes       = $request->notes ? trim($request->notes) : null;

        // All players whose subscription is not expired and not frozen
        $players = Player::whereHas('subscription', function ($q) {
            $q->where('is_frozen', false)->where('status', '!=', 'expired');
        }, '>=', 1)->orderBy('full_name')->get();

        if ($players->isEmpty()) {
            return redirect()->route('social.schedule')->with('error', 'لا يوجد طلاب لديهم اشتراك فعال حالياً');
        }

        $rows = [];
        foreach ($players as $player) {
            $rows[] = [
                'player_id'          => $player->id,
                'custom_description' => $description,
                'scheduled_date'     => null,
                'status'             => 'pending',
                'notes'              => $notes,
                'published_at'       => null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        SocialContent::insert($rows);

        return redirect()->route('social.schedule')->with('success', 'تم إنشاء قائمة "' . $description . '" بـ ' . count($rows) . ' طالب');
    }

    /* ── Single Entry Add ── */

    public function store(Request $request)
    {
        $request->validate([
            'player_id'          => 'required|exists:players,id',
            'custom_description' => 'required|string|max:255',
            'notes'              => 'nullable|string|max:500',
        ]);

        SocialContent::create([
            'player_id'          => $request->player_id,
            'custom_description' => $request->custom_description,
            'scheduled_date'     => null,
            'status'             => 'pending',
            'notes'              => $request->notes,
            'published_at'       => null,
        ]);

        return redirect()->route('social.schedule')->with('success', 'تم إضافة الجلسة بنجاح');
    }

    /* ── Edit ── */

    public function update(Request $request, SocialContent $social)
    {
        $request->validate([
            'player_id'          => 'required|exists:players,id',
            'custom_description' => 'required|string|max:255',
            'notes'              => 'nullable|string|max:500',
        ]);

        $social->update([
            'player_id'          => $request->player_id,
            'custom_description' => $request->custom_description,
            'notes'              => $request->notes,
        ]);

        return redirect()->route('social.schedule')->with('success', 'تم تحديث الجلسة');
    }

    /* ── Mark Published — supports AJAX ── */

    public function markPublished(Request $request, SocialContent $social)
    {
        $social->update([
            'status'       => 'published',
            'published_at' => now()->toDateString(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'published']);
        }

        return redirect()->route('social.schedule')->with('success', 'تم تأكيد النشر');
    }

    /* ── Mark ALL pending for a description as published — AJAX ── */

    public function markAll(Request $request)
    {
        $request->validate(['description' => 'required|string|max:255']);

        SocialContent::where('custom_description', '=', $request->description)
            ->where('status', '!=', 'published')
            ->update([
                'status'       => 'published',
                'published_at' => now()->toDateString(),
            ]);

        return response()->json(['ok' => true]);
    }

    /* ── Reset all published in a description back to pending ── */

    public function resetGroup(Request $request)
    {
        $request->validate(['description' => 'required|string|max:255']);

        SocialContent::where('custom_description', '=', $request->description)
            ->where('status', 'published')
            ->update(['status' => 'pending', 'published_at' => null]);

        return redirect()->route('social.schedule')->with('success', 'تم بدء جولة جديدة');
    }

    /* ── Delete entire group by description ── */

    public function deleteGroup(Request $request)
    {
        $request->validate(['description' => 'required|string|max:255']);

        SocialContent::where('custom_description', '=', $request->description)->delete();

        return redirect()->route('social.schedule')->with('success', 'تم حذف القائمة.');
    }

    /* ── Delete ── */

    public function destroy(SocialContent $social)
    {
        $social->delete();
        return redirect()->route('social.schedule')->with('success', 'تم حذف الجلسة');
    }
}
