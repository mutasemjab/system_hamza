@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('contentheader', 'لوحة التحكم')
@section('contentheaderlink', '<a href="'.route('admin.dashboard').'">الرئيسية</a>')
@section('contentheaderactive', 'Dashboard')

@section('content')

{{-- Stats --}}
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-indigo">
            <div class="stat-card-icon"><i class="fas fa-running"></i></div>
            <div>
                <div class="stat-card-value">{{ $totalPlayers }}</div>
                <div class="stat-card-label">إجمالي اللاعبين</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-emerald">
            <div class="stat-card-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <div class="stat-card-value">{{ number_format($totalCollected, 0) }}</div>
                <div class="stat-card-label">المبالغ المحصّلة (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-rose">
            <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-card-value">{{ number_format($totalPending, 0) }}</div>
                <div class="stat-card-label">مبالغ متبقية (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-amber">
            <div class="stat-card-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="stat-card-value">{{ $totalUsers }}</div>
                <div class="stat-card-label">المستخدمون</div>
            </div>
        </div>
    </div>
</div>

{{-- Birthday Reminders --}}
@if($birthdayPlayers->count())
<div class="birthday-banner mb-4">
    <div class="birthday-banner-icon"><i class="fas fa-birthday-cake"></i></div>
    <div style="flex:1;min-width:0">
        <div style="font-size:13px;font-weight:700;color:#be185d;margin-bottom:8px">
            <i class="fas fa-bell mr-1"></i> أعياد ميلاد قادمة
        </div>
        <div class="birthday-list">
            @foreach($birthdayPlayers as $player)
            <div class="birthday-chip">
                <div class="birthday-chip-avatar">{{ $player->initials }}</div>
                <div>
                    <div style="font-size:12.5px;font-weight:600;color:#1e293b">{{ $player->full_name }}</div>
                    <div class="birthday-chip-when">
                        @if($player->days_until_birthday == 0)
                            🎉 اليوم!
                        @elseif($player->days_until_birthday == 1)
                            🥳 بكرا!
                        @elseif($player->days_until_birthday == 2)
                            😊 بعد بكرا
                        @else
                            {{ $player->next_birthday->translatedFormat('d M') }}
                            &nbsp;·&nbsp; بعد {{ $player->days_until_birthday }} أيام
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="row">
    {{-- Social Media Upcoming --}}
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-photo-film text-accent mr-2"></i>
                    <span class="card-title">الجلسات القادمة</span>
                    <span class="badge badge-secondary ml-2">{{ $socialPendingCount }} قادم · {{ $socialPublishedCount }} منشور</span>
                </div>
                <a href="{{ route('social.schedule') }}" class="btn btn-secondary btn-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="card-body" style="padding:12px 22px !important">
                @forelse($socialUpcoming as $item)
                @php
                    $arabicDays = ['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
                @endphp
                <div class="social-upcoming-row">
                    <div style="min-width:70px;text-align:center">
                        <div style="font-size:13px;font-weight:700;color:var(--text-primary)">
                            {{ $item->scheduled_date?->format('m/d') }}
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">
                            {{ $item->scheduled_date ? $arabicDays[$item->scheduled_date->dayOfWeek] : '' }}
                        </div>
                    </div>
                    <div class="late-sub-avatar" style="background:linear-gradient(135deg,#0c3c2c,#1a7a55)">
                        {{ $item->player?->initials }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600">{{ $item->player?->full_name }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ $item->custom_description }}</div>
                    </div>
                    @if($item->scheduled_date && $item->scheduled_date->isToday())
                        <span class="badge badge-success">اليوم</span>
                    @elseif($item->scheduled_date && $item->scheduled_date->isTomorrow())
                        <span class="badge badge-primary">بكرا</span>
                    @endif
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-calendar-check" style="font-size:32px;color:var(--success);opacity:.5;display:block;margin-bottom:8px"></i>
                    <span style="font-size:13px;color:var(--text-muted)">لا توجد جلسات قادمة</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Late Subscriptions Alert --}}
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                    <span class="card-title">اشتراكات متأخرة</span>
                </div>
                <a href="{{ route('subscriptions.index', ['status' => 'late']) }}" class="btn btn-secondary btn-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="card-body" style="padding:12px 22px !important">
                @forelse($lateSubscriptions as $sub)
                <div class="late-sub-row">
                    <div class="late-sub-avatar">{{ $sub->player?->initials }}</div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600">{{ $sub->player?->full_name }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">
                            متبقي:
                            <strong style="color:var(--danger)">{{ number_format($sub->remaining_amount, 0) }} د.أ</strong>
                        </div>
                    </div>
                    <a href="{{ route('subscriptions.edit', $sub) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-check-circle" style="font-size:32px;color:var(--success);opacity:.5;display:block;margin-bottom:8px"></i>
                    <span style="font-size:13px;color:var(--text-muted)">لا توجد اشتراكات متأخرة 🎉</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Players --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-running text-accent mr-2"></i>
                    <span class="card-title">آخر اللاعبين المضافين</span>
                </div>
                <a href="{{ route('players.index') }}" class="btn btn-secondary btn-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="card-body" style="padding:0 !important">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>اللاعب</th>
                                <th>الهاتف</th>
                                <th>الوزن / الطول</th>
                                <th>الاشتراك</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPlayers as $player)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center" style="gap:10px">
                                        @if($player->photo_url)
                                            <img src="{{ $player->photo_url }}" style="width:36px;height:36px;border-radius:9px;object-fit:cover">
                                        @else
                                            <div class="dash-player-avatar">{{ $player->initials }}</div>
                                        @endif
                                        <div>
                                            <div style="font-size:13px;font-weight:600">{{ $player->full_name }}</div>
                                            @if($player->birth_date)
                                                <div style="font-size:11px;color:var(--text-muted)">{{ $player->age }} سنة</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:13px">{{ $player->phone ?? '—' }}</td>
                                <td style="font-size:13px">
                                    @if($player->weight || $player->height)
                                        {{ $player->weight ? $player->weight.'kg' : '' }}
                                        {{ $player->weight && $player->height ? '/' : '' }}
                                        {{ $player->height ? $player->height.'cm' : '' }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($player->subscription)
                                        @php $badge = $player->subscription->status_badge; @endphp
                                        <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                    @else
                                        <span class="badge badge-secondary">لا يوجد</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('players.edit', $player) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">لا يوجد لاعبون بعد</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('css')
<style>
/* ── Birthday Banner ── */
.birthday-banner {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    background: linear-gradient(135deg, #fdf2f8, #fce7f3);
    border: 1.5px solid #fbcfe8;
    border-radius: 14px;
    padding: 16px 20px;
    box-shadow: 0 2px 12px rgba(236,72,153,.08);
}
.birthday-banner-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ec4899, #be185d);
    color: #fff; font-size: 20px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(236,72,153,.3);
}
.birthday-list {
    display: flex; flex-wrap: wrap; gap: 10px;
}
.birthday-chip {
    display: flex; align-items: center; gap: 8px;
    background: #fff;
    border: 1px solid #fbcfe8;
    border-radius: 10px;
    padding: 8px 12px;
    transition: box-shadow .15s;
}
.birthday-chip:hover { box-shadow: 0 2px 8px rgba(236,72,153,.12); }
.birthday-chip-avatar {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #ec4899, #be185d);
    color: #fff; font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.birthday-chip-when {
    font-size: 11.5px; color: #9d174d; font-weight: 600; margin-top: 1px;
}

/* ── Social Upcoming ── */
.social-upcoming-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid #f1f5f9;
}
.social-upcoming-row:last-child { border-bottom: none; }
.late-sub-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid #f1f5f9;
}
.late-sub-row:last-child { border-bottom: none; }
.late-sub-avatar {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dash-player-avatar {
    width: 36px; height: 36px; border-radius: 9px;
    background: linear-gradient(135deg, #0c3c2c, #1a7a55);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
</style>
@endsection
