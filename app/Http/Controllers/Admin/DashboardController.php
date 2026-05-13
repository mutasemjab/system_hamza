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
        $lateSubscriptions  = Subscription::where('status', 'late')->with('player')->latest()->limit(5)->get();
        $recentPlayers      = Player::with('subscription')->latest()->limit(6)->get();

        // Social board summary (per typed content)
        $socialSummary = [];
        foreach (SocialContent::$types as $type => $meta) {
            $socialSummary[$type] = [
                'meta'      => $meta,
                'current'   => SocialContent::with('player')->where('content_type', $type)->where('status', 'next')->first(),
                'pending'   => SocialContent::where('content_type', $type)->where('status', 'pending')->count(),
                'published' => SocialContent::where('content_type', $type)->where('status', 'published')->count(),
            ];
        }

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
            'recentPlayers',
            'socialSummary',
            'birthdayPlayers',
            'frozenCount'
        ));
    }
}
