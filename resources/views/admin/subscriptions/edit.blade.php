@extends('layouts.admin')
@section('title', 'تعديل الاشتراك')

@section('contentheader', 'تعديل الاشتراك')
@section('contentheaderactive', 'تعديل')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.subscription.update', $subscription) }}">
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
                    <a href="{{ route('admin.subscription.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right mr-1"></i> رجوع
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
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
