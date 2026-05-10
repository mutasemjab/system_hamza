<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Subscription;
use App\Models\SocialContent;
use App\Models\User;
use Illuminate\Http\Request;

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

        // Social board summary (how many published total per type)
        $socialSummary = [];
        foreach (SocialContent::$types as $type => $meta) {
            $socialSummary[$type] = [
                'meta'      => $meta,
                'current'   => SocialContent::with('player')->where('content_type', $type)->where('status', 'next')->first(),
                'pending'   => SocialContent::where('content_type', $type)->where('status', 'pending')->count(),
                'published' => SocialContent::where('content_type', $type)->where('status', 'published')->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'totalPlayers',
            'totalUsers',
            'totalCollected',
            'totalPending',
            'lateSubscriptions',
            'recentPlayers',
            'socialSummary'
        ));
    }
}
