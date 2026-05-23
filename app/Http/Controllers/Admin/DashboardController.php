<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Subscription;
use App\Models\SocialContent;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPlayers       = Player::count();
        $totalUsers         = User::count();
        $totalCollected     = Subscription::sum('paid_amount');
        $totalPending       = Subscription::selectRaw('SUM(total_amount - paid_amount) as r')->value('r') ?? 0;
        $lateSubscriptions    = Subscription::where('status', 'late')->with('player')->latest()->limit(5)->get();
        $expiredSubscriptions = Subscription::where('status', 'expired')
            ->with('player')
            ->orderByDesc('end_date')
            ->limit(8)
            ->get();
        $expiredCount = Subscription::where('status', 'expired')->count();
        $recentPlayers      = Player::with('subscription')->latest()->limit(6)->get();

        $socialPendingCount   = SocialContent::where('status', '=', 'pending', 'and')->count();
        $socialPublishedCount = SocialContent::where('status', '=', 'published', 'and')->count();

        // Sample of pending entries grouped by description for dashboard overview
        $socialPendingSample = SocialContent::with('player')
            ->where('status', '=', 'pending', 'and')
            ->latest()
            ->limit(6)
            ->get();

        // Birthday reminders — players whose birthday falls within the next 7 days
        $today = Carbon::today();
        $birthdayPlayers = Player::whereNotNull('birth_date')
            ->get()
            ->map(function ($player) use ($today) {
                $bday = $player->birth_date->copy()->setYear($today->year);
                if ($bday->lt($today)) {
                    $bday->addYear();
                }
                $player->days_until_birthday = $today->diffInDays($bday);
                $player->next_birthday       = $bday;
                return $player;
            })
            ->filter(fn($p) => $p->days_until_birthday <= 7)
            ->sortBy('days_until_birthday')
            ->values();

        // Frozen subscriptions count (for sidebar badge / awareness)
        $frozenCount = Subscription::where('is_frozen', true)->count();

        return view('admin.dashboard', compact(
            'totalPlayers',
            'totalUsers',
            'totalCollected',
            'totalPending',
            'lateSubscriptions',
            'expiredSubscriptions',
            'expiredCount',
            'recentPlayers',
            'socialPendingCount',
            'socialPublishedCount',
            'socialPendingSample',
            'birthdayPlayers',
            'frozenCount'
        ));
    }
}
