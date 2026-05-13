<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Subscription;
use Carbon\Carbon;
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
            if ($request->status === 'frozen') {
                $query->where('is_frozen', true);
            } else {
                $query->where('status', $request->status)->where('is_frozen', false);
            }
        }

        $subscriptions = $query->latest()->paginate(15);

        // Alerts: late or expiring soon (exclude frozen)
        $alerts = Subscription::with('player')
            ->where('is_frozen', false)
            ->where(fn($q) => $q->where('status', 'late')->orWhere('status', 'expired'))
            ->orWhere(fn($q) => $q->where('is_frozen', false)->whereNotNull('end_date'))
            ->get()
            ->filter(fn($s) => ($s->status !== 'active' || $s->is_expiring_soon) && !$s->is_frozen);

        $totalCollected = Subscription::sum('paid_amount');
        $totalPending   = Subscription::selectRaw('SUM(total_amount - paid_amount) as remaining')->value('remaining') ?? 0;
        $activeCount    = Subscription::where('status', 'active')->where('is_frozen', false)->count();
        $lateCount      = Subscription::where('status', 'late')->where('is_frozen', false)->count();
        $frozenCount    = Subscription::where('is_frozen', true)->count();

        return view('admin.subscriptions.index', compact(
            'subscriptions', 'alerts', 'totalCollected', 'totalPending',
            'activeCount', 'lateCount', 'frozenCount'
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

    /* ── Freeze ── */

    public function freeze(Request $request, Subscription $subscription)
    {
        if ($subscription->is_frozen) {
            return redirect()->back()->with('error', 'الاشتراك مجمّد بالفعل');
        }

        $request->validate([
            'freeze_date' => 'required|date',
            'freeze_note' => 'nullable|string|max:255',
        ]);

        $subscription->freeze(
            Carbon::parse($request->freeze_date),
            $request->freeze_note
        );

        return redirect()->route('subscriptions.index')
            ->with('success', 'تم تجميد الاشتراك بنجاح بتاريخ ' . Carbon::parse($request->freeze_date)->format('Y/m/d'));
    }

    /* ── Unfreeze / Resume ── */

    public function unfreeze(Subscription $subscription)
    {
        if (!$subscription->is_frozen) {
            return redirect()->back()->with('error', 'الاشتراك غير مجمّد');
        }

        $subscription->unfreeze();

        $newEnd = $subscription->fresh()->end_date;
        $msg = 'تم استئناف الاشتراك بنجاح.';
        if ($newEnd) {
            $msg .= ' تاريخ الانتهاء الجديد: ' . $newEnd->format('Y/m/d');
        }

        return redirect()->route('subscriptions.index')->with('success', $msg);
    }
}
