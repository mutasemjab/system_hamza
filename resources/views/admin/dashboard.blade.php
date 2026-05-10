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

<div class="row">
    {{-- Social Media Quick View --}}
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-photo-film text-accent mr-2"></i>
                    <span class="card-title">دور المحتوى الحالي</span>
                </div>
                <a href="{{ route('social.index') }}" class="btn btn-secondary btn-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($socialSummary as $type => $data)
                    @php $meta = $data['meta']; @endphp
                    <div class="col-sm-6 mb-3">
                        <div class="social-dash-card" style="--sc-color: {{ $meta['color'] }}">
                            <div class="social-dash-header">
                                <i class="{{ $meta['icon'] }}" style="color:{{ $meta['color'] }}"></i>
                                <span>{{ $meta['label'] }}</span>
                                <span class="social-dash-counts">
                                    {{ $data['pending'] }} قادم · {{ $data['published'] }} منشور
                                </span>
                            </div>
                            @if($data['current'])
                                <div class="social-dash-current">
                                    <div class="social-dash-avatar" style="background:linear-gradient(135deg,{{ $meta['color'] }},#8b5cf6)">
                                        {{ $data['current']->player?->initials }}
                                    </div>
                                    <div style="font-size:13px;font-weight:600">{{ $data['current']->player?->full_name }}</div>
                                </div>
                            @else
                                <div style="font-size:12px;color:var(--text-muted);padding:4px 0">لا يوجد دور محدد</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
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
.social-dash-card {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 14px;
    background: #fff;
    transition: box-shadow .2s;
}
.social-dash-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.social-dash-header {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 600; margin-bottom: 10px;
}
.social-dash-counts { font-size: 11px; color: var(--text-muted); font-weight: 400; margin-left: auto; }
.social-dash-current {
    display: flex; align-items: center; gap: 8px;
    background: #f8fafc; border-radius: 8px; padding: 8px 10px;
}
.social-dash-avatar {
    width: 30px; height: 30px; border-radius: 8px;
    color: #fff; font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
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
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
</style>
@endsection
