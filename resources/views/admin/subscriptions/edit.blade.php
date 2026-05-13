@extends('layouts.admin')
@section('title', 'تعديل الاشتراك')

@section('contentheader', 'تعديل الاشتراك')
@section('contentheaderactive', 'تعديل')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

    {{-- Frozen alert --}}
    @if($subscription->is_frozen)
    <div class="freeze-status-banner mb-3">
        <div class="freeze-status-icon"><i class="fas fa-snowflake fa-spin" style="animation-duration:3s"></i></div>
        <div style="flex:1">
            <div style="font-size:14px;font-weight:700;color:#075985">الاشتراك مجمّد حالياً</div>
            <div style="font-size:12.5px;color:#0369a1;margin-top:2px">
                منذ {{ $subscription->frozen_at?->format('Y/m/d') }}
                @if($subscription->frozen_days_remaining)
                    &nbsp;·&nbsp; {{ $subscription->frozen_days_remaining }} يوم متبقية في الاشتراك
                @endif
                @if($subscription->freeze_note)
                    &nbsp;·&nbsp; {{ $subscription->freeze_note }}
                @endif
            </div>
        </div>
        <form method="POST" action="{{ route('subscriptions.unfreeze', $subscription) }}"
              onsubmit="return confirm('استئناف الاشتراك وإضافة الأيام المتبقية من اليوم؟')">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-play mr-1"></i> استئناف الاشتراك
            </button>
        </form>
    </div>
    @endif

        <form method="POST" action="{{ route('subscriptions.update', $subscription) }}">
            @csrf @method('PUT')
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit text-accent mr-2"></i>
                    <span class="card-title">{{ $subscription->player?->full_name }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>إجمالي الاشتراك (د.أ) <span class="text-danger">*</span></label>
                            <input type="number" name="total_amount" step="0.01" min="0"
                                   class="form-control @error('total_amount') is-invalid @enderror"
                                   value="{{ old('total_amount', $subscription->total_amount) }}" id="totalAmount">
                            @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>المبلغ المدفوع (د.أ) <span class="text-danger">*</span></label>
                            <input type="number" name="paid_amount" step="0.01" min="0"
                                   class="form-control @error('paid_amount') is-invalid @enderror"
                                   value="{{ old('paid_amount', $subscription->paid_amount) }}" id="paidAmount">
                            @error('paid_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <div class="remaining-box">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:13px;color:var(--text-muted)">المبلغ المتبقي</span>
                                    <strong id="remainingDisplay" style="font-size:16px;color:var(--danger)">
                                        {{ number_format($subscription->remaining_amount, 2) }} د.أ
                                    </strong>
                                </div>
                                <div style="background:#e2e8f0;border-radius:99px;height:8px;margin-top:10px;overflow:hidden">
                                    <div id="progressBar" style="height:100%;width:{{ $subscription->payment_percent }}%;border-radius:99px;background:{{ $subscription->payment_percent >= 100 ? 'var(--success)' : ($subscription->payment_percent >= 50 ? 'var(--warning)' : 'var(--danger)') }};transition:width .3s"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>آخر تاريخ دفع</label>
                            <input type="date" name="last_payment_date"
                                   class="form-control @error('last_payment_date') is-invalid @enderror"
                                   value="{{ old('last_payment_date', $subscription->last_payment_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>حالة الاشتراك <span class="text-danger">*</span></label>
                            <select name="status" class="form-control no-select2 @error('status') is-invalid @enderror">
                                <option value="active"  {{ old('status', $subscription->status) == 'active'  ? 'selected' : '' }}>✅ فعال</option>
                                <option value="late"    {{ old('status', $subscription->status) == 'late'    ? 'selected' : '' }}>⚠️ متأخر</option>
                                <option value="expired" {{ old('status', $subscription->status) == 'expired' ? 'selected' : '' }}>❌ منتهي</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>تاريخ البداية</label>
                            <input type="date" name="start_date" class="form-control"
                                   value="{{ old('start_date', $subscription->start_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>تاريخ الانتهاء</label>
                            <input type="date" name="end_date" class="form-control"
                                   value="{{ old('end_date', $subscription->end_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-12 mb-0">
                            <label>ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $subscription->notes) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right mr-1"></i> رجوع
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>

        {{-- Freeze Card (only when not already frozen) --}}
        @if(!$subscription->is_frozen)
        <div class="card mt-4">
            <div class="card-header" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe)">
                <i class="fas fa-pause-circle" style="color:#0ea5e9"></i>
                <span class="card-title" style="color:#075985">تجميد الاشتراك</span>
            </div>
            <div class="card-body">
                <p style="font-size:13px;color:var(--text-muted);margin-bottom:14px">
                    عند التجميد يتوقف احتساب مدة الاشتراك. عند الاستئناف، تُضاف الأيام المتبقية تلقائياً من يوم الرجوع.
                </p>
                <form method="POST" action="{{ route('subscriptions.freeze', $subscription) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>تاريخ بداية التجميد</label>
                            <input type="date" name="freeze_date" class="form-control"
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>سبب التجميد (اختياري)</label>
                            <input type="text" name="freeze_note" class="form-control"
                                   placeholder="مثال: سفر، إصابة...">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm"
                            style="background:#0ea5e9;border-color:#0ea5e9;color:#fff"
                            onclick="return confirm('تأكيد تجميد الاشتراك؟')">
                        <i class="fas fa-pause mr-1"></i> تجميد الاشتراك
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('css')
<style>
.remaining-box {
    background: #f8fafc;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 14px 18px;
}
.freeze-status-banner {
    display: flex; align-items: center; gap: 14px;
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    border: 1.5px solid #7dd3fc;
    border-radius: 12px;
    padding: 14px 18px;
}
.freeze-status-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #fff; font-size: 18px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(14,165,233,.3);
}
</style>
@endsection

@section('js')
<script>
function updateRemaining() {
    const total = parseFloat(document.getElementById('totalAmount').value) || 0;
    const paid  = parseFloat(document.getElementById('paidAmount').value) || 0;
    const remaining = Math.max(0, total - paid);
    const pct = total > 0 ? Math.min(100, Math.round((paid / total) * 100)) : 0;
    document.getElementById('remainingDisplay').textContent = remaining.toLocaleString() + ' د.أ';
    const bar = document.getElementById('progressBar');
    bar.style.width = pct + '%';
    bar.style.background = pct >= 100 ? 'var(--success)' : pct >= 50 ? 'var(--warning)' : 'var(--danger)';
}
document.getElementById('totalAmount').addEventListener('input', updateRemaining);
document.getElementById('paidAmount').addEventListener('input', updateRemaining);
</script>
@endsection
