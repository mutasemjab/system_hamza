@extends('layouts.admin')
@section('title', 'إضافة اشتراك')

@section('contentheader', 'إضافة اشتراك جديد')
@section('contentheaderactive', 'إضافة')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.subscription.store') }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-file-invoice-dollar text-accent mr-2"></i>
                    <span class="card-title">بيانات الاشتراك</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label>اللاعب <span class="text-danger">*</span></label>
                            <select name="player_id" class="form-control @error('player_id') is-invalid @enderror">
                                <option value="">اختر اللاعب...</option>
                                @foreach($players as $player)
                                    <option value="{{ $player->id }}" {{ old('player_id') == $player->id ? 'selected' : '' }}>
                                        {{ $player->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('player_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($players->isEmpty())
                                <div class="text-warning mt-1" style="font-size:12px">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    جميع اللاعبين لديهم اشتراكات. أضف لاعباً جديداً أولاً.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>إجمالي الاشتراك (د.أ) <span class="text-danger">*</span></label>
                            <input type="number" name="total_amount" step="0.01" min="0"
                                   class="form-control @error('total_amount') is-invalid @enderror"
                                   value="{{ old('total_amount', 0) }}" id="totalAmount">
                            @error('total_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>المبلغ المدفوع (د.أ) <span class="text-danger">*</span></label>
                            <input type="number" name="paid_amount" step="0.01" min="0"
                                   class="form-control @error('paid_amount') is-invalid @enderror"
                                   value="{{ old('paid_amount', 0) }}" id="paidAmount">
                            @error('paid_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Live remaining --}}
                        <div class="col-12 mb-3">
                            <div class="remaining-box">
                                <div class="d-flex justify-content-between">
                                    <span style="font-size:13px;color:var(--text-muted)">المبلغ المتبقي</span>
                                    <strong id="remainingDisplay" style="font-size:16px;color:var(--danger)">0 د.أ</strong>
                                </div>
                                <div style="background:#e2e8f0;border-radius:99px;height:8px;margin-top:10px;overflow:hidden">
                                    <div id="progressBar" style="height:100%;width:0%;border-radius:99px;background:var(--accent);transition:width .3s"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>آخر تاريخ دفع</label>
                            <input type="date" name="last_payment_date"
                                   class="form-control @error('last_payment_date') is-invalid @enderror"
                                   value="{{ old('last_payment_date') }}">
                            @error('last_payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>حالة الاشتراك <span class="text-danger">*</span></label>
                            <select name="status" class="form-control no-select2 @error('status') is-invalid @enderror">
                                <option value="active"  {{ old('status','active') == 'active'  ? 'selected' : '' }}>✅ فعال</option>
                                <option value="late"    {{ old('status') == 'late'    ? 'selected' : '' }}>⚠️ متأخر</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>❌ منتهي</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>تاريخ البداية</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>تاريخ الانتهاء</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date') }}">
                        </div>

                        <div class="col-12 mb-0">
                            <label>ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="ملاحظات حول الاشتراك...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.subscription.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right mr-1"></i> رجوع
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> حفظ الاشتراك
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
    document.getElementById('remainingDisplay').textContent = remaining.toLocaleString('ar') + ' د.أ';
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('progressBar').style.background = pct >= 100 ? 'var(--success)' : pct >= 50 ? 'var(--warning)' : 'var(--danger)';
}
document.getElementById('totalAmount').addEventListener('input', updateRemaining);
document.getElementById('paidAmount').addEventListener('input', updateRemaining);
updateRemaining();
</script>
@endsection
