@extends('layouts.admin')
@section('title', 'الاشتراكات')

@section('contentheader', 'إدارة الاشتراكات')
@section('contentheaderactive', 'الاشتراكات')

@section('content')

{{-- Stats Row --}}
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-indigo">
            <div class="stat-card-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <div class="stat-card-value">{{ number_format($totalCollected, 0) }}</div>
                <div class="stat-card-label">إجمالي المحصّل (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-rose">
            <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-card-value">{{ number_format($totalPending, 0) }}</div>
                <div class="stat-card-label">المبالغ المتبقية (د.أ)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-emerald">
            <div class="stat-card-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-card-value">{{ $activeCount }}</div>
                <div class="stat-card-label">اشتراكات فعالة</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="stat-card stat-amber">
            <div class="stat-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div class="stat-card-value">{{ $lateCount }}</div>
                <div class="stat-card-label">اشتراكات متأخرة</div>
            </div>
        </div>
    </div>
</div>

{{-- Alerts --}}
@if($alerts->count())
<div class="card mb-3" style="border-left: 4px solid var(--warning) !important;">
    <div class="card-body" style="padding:16px 22px !important">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-bell text-warning mr-2" style="font-size:18px"></i>
            <strong style="font-size:14px">تنبيهات الدفعات المستحقة</strong>
        </div>
        <div class="row">
            @foreach($alerts->take(6) as $alert)
            <div class="col-md-4 mb-2">
                <div class="d-flex align-items-center" style="gap:10px;background:#fffbeb;border-radius:8px;padding:10px 12px">
                    <div class="player-avatar-sm">{{ $alert->player?->initials }}</div>
                    <div>
                        <div style="font-size:13px;font-weight:600">{{ $alert->player?->full_name }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">
                            متبقي: <strong style="color:var(--danger)">{{ number_format($alert->remaining_amount, 0) }} د.أ</strong>
                            @if($alert->is_expiring_soon)
                                &nbsp;· <span style="color:var(--warning)">ينتهي قريباً</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body" style="padding:14px 22px !important">
        <form method="GET" action="{{ route('admin.subscription.index') }}" class="d-flex flex-wrap" style="gap:10px;align-items:center">
            <input type="text" name="search" class="form-control" style="max-width:260px"
                   placeholder="بحث باسم اللاعب..." value="{{ request('search') }}">
            <select name="status" class="form-control no-select2" style="max-width:180px">
                <option value="">جميع الحالات</option>
                <option value="active"  {{ request('status') == 'active'  ? 'selected' : '' }}>فعال</option>
                <option value="late"    {{ request('status') == 'late'    ? 'selected' : '' }}>متأخر</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search mr-1"></i> بحث
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.subscription.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-1"></i> إلغاء
            </a>
            @endif
            <a href="{{ route('admin.subscription.create') }}" class="btn btn-primary ml-auto">
                <i class="fas fa-plus mr-1"></i> إضافة اشتراك
            </a>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body" style="padding:0 !important">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اللاعب</th>
                        <th>إجمالي الاشتراك</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>نسبة السداد</th>
                        <th>آخر دفعة</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                    <tr>
                        <td class="text-muted" style="font-size:12px">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:10px">
                                <div class="player-avatar-sm">{{ $sub->player?->initials }}</div>
                                <div style="font-weight:600;font-size:13px">{{ $sub->player?->full_name }}</div>
                            </div>
                        </td>
                        <td style="font-weight:600">{{ number_format($sub->total_amount, 0) }} <small class="text-muted">د.أ</small></td>
                        <td class="text-success" style="font-weight:600">{{ number_format($sub->paid_amount, 0) }} <small class="text-muted">د.أ</small></td>
                        <td style="font-weight:600;color:{{ $sub->remaining_amount > 0 ? 'var(--danger)' : 'var(--success)' }}">
                            {{ number_format($sub->remaining_amount, 0) }} <small class="text-muted">د.أ</small>
                        </td>
                        <td style="min-width:130px">
                            <div class="d-flex align-items-center" style="gap:8px">
                                <div style="flex:1;background:#e2e8f0;border-radius:99px;height:6px;overflow:hidden">
                                    <div style="height:100%;width:{{ $sub->payment_percent }}%;border-radius:99px;background:{{ $sub->payment_percent >= 100 ? 'var(--success)' : ($sub->payment_percent >= 50 ? 'var(--warning)' : 'var(--danger)') }}"></div>
                                </div>
                                <span style="font-size:12px;font-weight:600;min-width:35px">{{ $sub->payment_percent }}%</span>
                            </div>
                        </td>
                        <td style="font-size:13px">
                            {{ $sub->last_payment_date ? $sub->last_payment_date->format('Y/m/d') : '—' }}
                        </td>
                        <td style="font-size:13px">
                            @if($sub->end_date)
                                <span style="{{ $sub->is_expiring_soon ? 'color:var(--warning);font-weight:600' : '' }}">
                                    {{ $sub->end_date->format('Y/m/d') }}
                                </span>
                                @if($sub->is_expiring_soon)
                                    <i class="fas fa-exclamation-circle text-warning ml-1" title="ينتهي قريباً"></i>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php $badge = $sub->status_badge; @endphp
                            <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td>
                            <div class="d-flex" style="gap:6px">
                                <a href="{{ route('admin.subscription.edit', $sub) }}" class="btn btn-sm btn-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.subscription.destroy', $sub) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الاشتراك؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-file-invoice-dollar" style="font-size:36px;opacity:.3;display:block;margin-bottom:10px;color:var(--text-muted)"></i>
                            <span class="text-muted">لا توجد اشتراكات بعد</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($subscriptions->hasPages())
    <div class="card-footer">
        {{ $subscriptions->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection

@section('css')
<style>
.player-avatar-sm {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>
@endsection
