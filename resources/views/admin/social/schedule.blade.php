@extends('layouts.admin')
@section('title', 'جدول المحتوى')

@section('contentheader', 'إدارة جدول المحتوى')
@section('contentheaderactive', 'السوشال ميديا')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:13px">
        <i class="fas fa-info-circle mr-1"></i>
        جدولة المحتوى وتوزيعه على الطلاب بالتناوب
    </p>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus mr-2"></i> إضافة جلسة
    </button>
</div>

{{-- Generator Card --}}
<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-plus text-accent mr-2"></i>
                <span class="card-title">توليد جدول تلقائي</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('social.schedule.generate') }}" id="scheduleForm">
                    @csrf

                    {{-- Description --}}
                    <div class="form-group mb-4">
                        <label>نوع المحتوى / الوصف <span class="text-danger">*</span></label>
                        <input type="text" name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description') }}"
                               placeholder="مثال: ستوري، ريلز، كاروسيل، فيديو تمرين...">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Players --}}
                    <div class="form-group mb-4">
                        <label>الطلاب المشاركون <span class="text-danger">*</span></label>
                        <select name="player_ids[]" class="form-control @error('player_ids') is-invalid @enderror"
                                multiple id="playerSelect" style="height:auto">
                            @foreach($players as $player)
                                <option value="{{ $player->id }}"
                                        {{ in_array($player->id, old('player_ids', [])) ? 'selected' : '' }}>
                                    {{ $player->full_name }}
                                </option>
                            @endforeach
                        </select>
                        <div style="font-size:11.5px;color:var(--text-muted);margin-top:6px">
                            <i class="fas fa-info-circle mr-1"></i>
                            سيتم التناوب بين الطلاب بالترتيب الذي اخترتهم
                        </div>
                        @error('player_ids')
                            <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Days of Week --}}
                    <div class="form-group mb-4">
                        <label>أيام الأسبوع <span class="text-danger">*</span></label>
                        @php
                            $dayNames = [
                                0 => 'الأحد',    1 => 'الاثنين', 2 => 'الثلاثاء',
                                3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت',
                            ];
                            $oldDays = old('days', []);
                        @endphp
                        <div class="days-grid">
                            @foreach($dayNames as $num => $name)
                            <label class="day-chip {{ in_array($num, $oldDays) ? 'selected' : '' }}">
                                <input type="checkbox" name="days[]" value="{{ $num }}" class="d-none"
                                       {{ in_array($num, $oldDays) ? 'checked' : '' }}>
                                {{ $name }}
                            </label>
                            @endforeach
                        </div>
                        @error('days')
                            <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date Range --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label>من تاريخ</label>
                                <input type="date" name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', date('Y-m-d')) }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label>إلى تاريخ</label>
                                <input type="date" name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', date('Y-m-d', strtotime('+1 month'))) }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Notes for all generated entries --}}
                    <div class="form-group mb-4">
                        <label>ملاحظة للجلسات (اختياري)</label>
                        <input type="text" name="notes"
                               class="form-control"
                               value="{{ old('notes') }}"
                               placeholder="ملاحظة تُضاف لجميع الجلسات المولّدة...">
                    </div>

                    {{-- Preview Counter --}}
                    <div class="schedule-preview-box mb-4" id="previewBox" style="display:none">
                        <i class="fas fa-calendar-check mr-2" style="color:var(--success)"></i>
                        سيتم توليد تقريباً <strong id="previewCount">0</strong> جلسة
                        للفترة من <span id="previewFrom"></span> إلى <span id="previewTo"></span>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-magic mr-1"></i> توليد الجدول
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Help Card --}}
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-lightbulb text-warning mr-2"></i>
                <span class="card-title">كيف يعمل؟</span>
            </div>
            <div class="card-body" style="font-size:13.5px;color:var(--text-muted);line-height:2">
                <div class="help-step">
                    <span class="help-num">١</span>
                    اكتب نوع المحتوى بحرية — مثلاً: <strong>ستوري</strong>، <strong>ريلز أسبوعي</strong>...
                </div>
                <div class="help-step">
                    <span class="help-num">٢</span>
                    اختر الطلاب المشاركين بالترتيب الذي تريده.
                </div>
                <div class="help-step">
                    <span class="help-num">٣</span>
                    حدد أيام الأسبوع — مثلاً الاثنين والخميس.
                </div>
                <div class="help-step">
                    <span class="help-num">٤</span>
                    حدد نطاق التاريخ. الافتراضي من اليوم إلى الشهر القادم.
                </div>
                <div class="help-step">
                    <span class="help-num">٥</span>
                    اضغط <strong>توليد</strong> — سيتناوب الطلاب تلقائياً على كل يوم مجدول.
                </div>
                <div style="margin-top:14px;padding:12px;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0;color:#15803d;font-size:12.5px">
                    <i class="fas fa-check-circle mr-1"></i>
                    مثال: أحمد، محمد، حسين ← كل اثنين وخميس → جلسة 1: أحمد، جلسة 2: محمد، جلسة 3: حسين، جلسة 4: أحمد...
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Schedule Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-list-alt text-accent mr-2"></i>
            <span class="card-title">الجلسات المجدولة</span>
            <span class="badge badge-secondary ml-2">{{ $scheduled->total() }}</span>
        </div>
    </div>
    @if($scheduled->count())
    <div class="card-body" style="padding:0 !important">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>اليوم</th>
                        <th>الطالب</th>
                        <th>النوع / الوصف</th>
                        <th>الحالة</th>
                        <th>ملاحظة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $arabicDays = ['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
                    @endphp
                    @foreach($scheduled as $item)
                    @php
                        $isPast = $item->scheduled_date && $item->scheduled_date->isPast()
                                  && $item->status !== 'published';
                    @endphp
                    <tr class="{{ $isPast ? 'past-row' : '' }}">
                        <td style="font-size:13px;font-weight:600;white-space:nowrap">
                            {{ $item->scheduled_date?->format('Y/m/d') ?? '—' }}
                            @if($item->scheduled_date && $item->scheduled_date->isToday())
                                <span class="badge badge-success ml-1">اليوم</span>
                            @elseif($item->scheduled_date && $item->scheduled_date->isTomorrow())
                                <span class="badge badge-primary ml-1">بكرا</span>
                            @endif
                        </td>
                        <td style="font-size:12.5px;color:var(--text-muted)">
                            {{ $item->scheduled_date ? $arabicDays[$item->scheduled_date->dayOfWeek] : '—' }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:8px">
                                <div class="player-avatar-sm">{{ $item->player?->initials }}</div>
                                <span style="font-size:13px;font-weight:600">{{ $item->player?->full_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-primary" style="font-size:12px">
                                {{ $item->custom_description }}
                            </span>
                        </td>
                        <td>
                            @if($item->status === 'published')
                                <span class="badge badge-success">
                                    <i class="fas fa-check mr-1"></i> تم النشر
                                </span>
                                @if($item->published_at)
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px">
                                        {{ $item->published_at->format('Y/m/d') }}
                                    </div>
                                @endif
                            @elseif($isPast)
                                <span class="badge badge-danger">متأخر</span>
                            @else
                                <span class="badge badge-secondary">قادم</span>
                            @endif
                        </td>
                        <td style="max-width:160px">
                            @if($item->notes)
                                <span style="font-size:12px;color:var(--text-muted)">{{ $item->notes }}</span>
                            @else
                                <span style="color:#cbd5e1">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex" style="gap:6px">
                                @if($item->status !== 'published')
                                <form method="POST" action="{{ route('social.markPublished', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="تم النشر">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <button type="button"
                                        class="btn btn-sm btn-primary edit-btn"
                                        title="تعديل"
                                        data-id="{{ $item->id }}"
                                        data-player="{{ $item->player_id }}"
                                        data-description="{{ $item->custom_description }}"
                                        data-date="{{ $item->scheduled_date?->format('Y-m-d') }}"
                                        data-status="{{ $item->status }}"
                                        data-notes="{{ $item->notes }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('social.destroy', $item) }}"
                                      onsubmit="return confirm('حذف هذه الجلسة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($scheduled->hasPages())
    <div class="card-footer">
        {{ $scheduled->links() }}
    </div>
    @endif
    @else
    <div class="card-body text-center py-5">
        <i class="fas fa-calendar-alt" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px;color:var(--text-muted)"></i>
        <span class="text-muted">لا توجد جلسات مجدولة بعد</span>
    </div>
    @endif
</div>

{{-- Add Single Entry Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,var(--accent),var(--accent-dark));border:none;padding:20px 24px">
                <h5 class="modal-title text-white" style="font-size:15px;font-weight:700">
                    <i class="fas fa-plus-circle mr-2"></i> إضافة جلسة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('social.store') }}">
                @csrf
                <div class="modal-body" style="padding:24px">
                    <div class="form-group mb-3">
                        <label>الطالب <span class="text-danger">*</span></label>
                        <select name="player_id" class="form-control" required>
                            <option value="">اختر الطالب...</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>النوع / الوصف <span class="text-danger">*</span></label>
                        <input type="text" name="custom_description" class="form-control" required
                               placeholder="مثال: ستوري، ريلز، فيديو تمرين...">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>التاريخ <span class="text-danger">*</span></label>
                                <input type="date" name="scheduled_date" class="form-control"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>الحالة <span class="text-danger">*</span></label>
                                <select name="status" class="form-control no-select2">
                                    <option value="pending">قادم</option>
                                    <option value="published">تم النشر</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>ملاحظة (اختياري)</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="أي ملاحظة إضافية..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding:14px 24px;border-top:1px solid var(--border-color)">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> إضافة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Entry Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,#f59e0b,#d97706);border:none;padding:20px 24px">
                <h5 class="modal-title text-white" style="font-size:15px;font-weight:700">
                    <i class="fas fa-edit mr-2"></i> تعديل الجلسة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="modal-body" style="padding:24px">
                    <div class="form-group mb-3">
                        <label>الطالب <span class="text-danger">*</span></label>
                        <select name="player_id" id="editPlayer" class="form-control" required>
                            <option value="">اختر الطالب...</option>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>النوع / الوصف <span class="text-danger">*</span></label>
                        <input type="text" name="custom_description" id="editDescription" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>التاريخ <span class="text-danger">*</span></label>
                                <input type="date" name="scheduled_date" id="editDate" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>الحالة <span class="text-danger">*</span></label>
                                <select name="status" id="editStatus" class="form-control no-select2">
                                    <option value="pending">قادم</option>
                                    <option value="published">تم النشر</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>ملاحظة (اختياري)</label>
                        <textarea name="notes" id="editNotes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding:14px 24px;border-top:1px solid var(--border-color)">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
/* ── Days Grid ── */
.days-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.day-chip {
    display: inline-flex; align-items: center;
    padding: 8px 16px;
    border: 2px solid var(--border-color);
    border-radius: 20px;
    font-size: 13px; font-weight: 500;
    color: var(--text-muted);
    cursor: pointer; transition: all .18s; user-select: none; margin: 0;
}
.day-chip:hover { border-color: var(--accent); color: var(--accent); }
.day-chip.selected {
    border-color: var(--accent);
    background: var(--accent);
    color: #fff;
}
/* ── Preview Box ── */
.schedule-preview-box {
    background: #f0fdf4; border: 1.5px solid #bbf7d0;
    border-radius: 10px; padding: 12px 16px;
    font-size: 13.5px; color: #15803d;
}
/* ── Help Steps ── */
.help-step { display: flex; align-items: baseline; gap: 10px; padding: 4px 0; }
.help-num {
    width: 24px; height: 24px; border-radius: 50%;
    background: var(--accent); color: #fff; font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
/* ── Past row ── */
.past-row td { opacity: .6; }
.past-row:hover td { opacity: .85; }
/* ── Avatar ── */
.player-avatar-sm {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg, #0c3c2c, #1a7a55);
    color: #fff; font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
</style>
@endsection

@section('js')
<script>
/* Day chip toggle — e.preventDefault() stops the browser from double-toggling
   the hidden checkbox (label wrapping an input fires two toggle events) */
document.querySelectorAll('.day-chip').forEach(chip => {
    chip.addEventListener('click', function (e) {
        e.preventDefault();
        this.classList.toggle('selected');
        this.querySelector('input[type="checkbox"]').checked = this.classList.contains('selected');
        updatePreview();
    });
});

/* Select2 for players */
jQuery(function($) {
    $('#playerSelect').select2({
        width: '100%',
        placeholder: 'اختر الطلاب...',
        dir: '{{ app()->getLocale() === "ar" ? "rtl" : "ltr" }}',
    }).on('change', updatePreview);
});

/* Live preview counter */
function updatePreview() {
    const startVal = document.querySelector('[name="start_date"]').value;
    const endVal   = document.querySelector('[name="end_date"]').value;
    const days     = Array.from(document.querySelectorAll('.day-chip.selected input'))
                         .map(i => parseInt(i.value));

    if (!startVal || !endVal || days.length === 0) {
        document.getElementById('previewBox').style.display = 'none';
        return;
    }

    let count = 0;
    let cursor = new Date(startVal);
    const end = new Date(endVal);

    while (cursor <= end) {
        if (days.includes(cursor.getDay())) count++;
        cursor.setDate(cursor.getDate() + 1);
    }

    document.getElementById('previewCount').textContent = count;
    document.getElementById('previewFrom').textContent  = startVal;
    document.getElementById('previewTo').textContent    = endVal;
    document.getElementById('previewBox').style.display = count > 0 ? 'block' : 'none';
}

document.querySelector('[name="start_date"]').addEventListener('change', updatePreview);
document.querySelector('[name="end_date"]').addEventListener('change', updatePreview);
updatePreview();

/* Edit modal */
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id          = this.dataset.id;
        const player      = this.dataset.player;
        const description = this.dataset.description;
        const date        = this.dataset.date;
        const status      = this.dataset.status;
        const notes       = this.dataset.notes;

        document.getElementById('editForm').action =
            '{{ route("social.update", ":id") }}'.replace(':id', id);

        document.getElementById('editPlayer').value      = player;
        document.getElementById('editDescription').value = description;
        document.getElementById('editDate').value        = date;
        document.getElementById('editStatus').value      = status;
        document.getElementById('editNotes').value       = notes || '';

        jQuery('#editModal').modal('show');
    });
});
</script>
@endsection
