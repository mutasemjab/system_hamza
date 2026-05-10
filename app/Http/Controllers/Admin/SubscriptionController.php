<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with('player');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('player', fn($q) => $q->where('full_name', 'like', "%$s%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(15);

        // Alerts: late or expiring soon
        $alerts = Subscription::with('player')
            ->where(fn($q) => $q->where('status', 'late')->orWhere('status', 'expired'))
            ->orWhereNotNull('end_date')
            ->get()
            ->filter(fn($s) => $s->status !== 'active' || $s->is_expiring_soon);

        $totalCollected  = Subscription::sum('paid_amount');
        $totalPending    = Subscription::selectRaw('SUM(total_amount - paid_amount) as remaining')->value('remaining') ?? 0;
        $activeCount     = Subscription::where('status', 'active')->count();
        $lateCount       = Subscription::where('status', 'late')->count();

        return view('admin.subscriptions.index', compact(
            'subscriptions', 'alerts', 'totalCollected', 'totalPending', 'activeCount', 'lateCount'
        ));
    }

    public function create()
    {
        $players = Player::doesntHave('subscription')->orderBy('full_name')->get();
        return view('admin.subscriptions.create', compact('players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'player_id'         => 'required|exists:players,id|unique:subscriptions,player_id',
            'total_amount'      => 'required|numeric|min:0',
            'paid_amount'       => 'required|numeric|min:0',
            'last_payment_date' => 'nullable|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'status'            => 'required|in:active,late,expired',
            'notes'             => 'nullable|string',
        ]);

        Subscription::create($request->all());

        return redirect()->route('subscriptions.index')
            ->with('success', 'تم إضافة الاشتراك بنجاح');
    }

    public function edit(Subscription $subscription)
    {
        $players = Player::orderBy('full_name')->get();
        return view('admin.subscriptions.edit', compact('subscription', 'players'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'total_amount'      => 'required|numeric|min:0',
            'paid_amount'       => 'required|numeric|min:0',
            'last_payment_date' => 'nullable|date',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'status'            => 'required|in:active,late,expired',
            'notes'             => 'nullable|string',
        ]);

        $subscription->update($request->except('player_id'));

        return redirect()->route('subscriptions.index')
            ->with('success', 'تم تحديث الاشتراك بنجاح');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')
            ->with('success', 'تم حذف الاشتراك');
    }
}
