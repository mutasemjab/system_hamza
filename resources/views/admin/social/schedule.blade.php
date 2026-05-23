@extends('layouts.admin')
@section('title', 'جدول المحتوى')

@section('contentheader', 'إدارة جدول المحتوى')
@section('contentheaderactive', 'السوشال ميديا')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:13px">
        <i class="fas fa-info-circle mr-1"></i>
        قوائم المحتوى — اختار الطالب وحطّ عليه صح لما تخلص
    </p>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
        <i class="fas fa-plus mr-2"></i> إضافة طالب
    </button>
</div>

{{-- Generator Card --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-magic text-accent mr-2"></i>
        <span class="card-title">إنشاء قائمة جديدة</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('social.schedule.generate') }}">
            @csrf
            <div class="row align-items-end" style="gap-y:12px">
                <div class="col-md-4">
                    <label class="form-label-sm">نوع المحتوى <span class="text-danger">*</span></label>
                    <input type="text" name="description"
                           class="form-control @error('description') is-invalid @enderror"
                           value="{{ old('description') }}"
                           placeholder="مثال: ستوري، ريلز، كاروسيل...">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-sm">ملاحظة (اختياري)</label>
                    <input type="text" name="notes" class="form-control"
                           value="{{ old('notes') }}"
                           placeholder="ملاحظة تُضاف لجميع الطلاب...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-users mr-1"></i> إنشاء
                    </button>
                </div>
            </div>
            <div class="mt-2" style="font-size:12px;color:var(--text-muted)">
                <i class="fas fa-info-circle mr-1"></i>
                سيتم إضافة جميع الطلاب الذين لديهم اشتراك فعال (غير منتهٍ وغير مجمَّد) تلقائياً.
            </div>
        </form>
    </div>
</div>

{{-- Grouped Queue Cards --}}
@php $totalEntries = $grouped->flatten()->count(); @endphp

@if($grouped->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-layer-group" style="font-size:40px;opacity:.15;display:block;margin-bottom:12px;color:var(--text-muted)"></i>
        <p class="text-muted mb-2">لا توجد قوائم بعد</p>
        <p class="text-muted small">أنشئ قائمتك الأولى من الفورم أعلاه</p>
    </div>
</div>
@else
@foreach($grouped as $description => $entries)
@php
    $gIdx    = $loop->index;
    $pending = $entries->where('status', 'pending');
    $allDone = $pending->isEmpty() && $entries->isNotEmpty();
@endphp
<div class="card mb-3" id="group-{{ $gIdx }}">

    {{-- Card Header --}}
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:8px">
        <div class="d-flex align-items-center" style="gap:10px">
            <span class="desc-badge">{{ $description }}</span>
            <span class="text-muted" style="font-size:12px" id="pending-count-{{ $gIdx }}">
                {{ $pending->count() }} متبقي من {{ $entries->count() }}
            </span>
        </div>
        <div class="d-flex" style="gap:6px">
            @if(!$allDone)
            <button type="button"
                    class="btn btn-sm btn-success mark-all-btn"
                    data-group="{{ $gIdx }}"
                    data-description="{{ $description }}">
                <i class="fas fa-check-double mr-1"></i> تأكيد الكل
            </button>
            @endif

            {{-- New round: reset published → pending --}}
            <form method="POST"
                  action="{{ route('social.resetGroup') }}"
                  class="d-inline {{ $allDone ? '' : 'd-none' }}"
                  id="reset-form-{{ $gIdx }}">
                @csrf
                <input type="hidden" name="description" value="{{ $description }}">
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-redo mr-1"></i> جولة جديدة
                </button>
            </form>

            {{-- Delete whole group --}}
            <form method="POST"
                  action="{{ route('social.deleteGroup') }}"
                  class="d-inline"
                  onsubmit="return confirm('حذف قائمة {{ addslashes($description) }} بالكامل؟')">
                @csrf
                <input type="hidden" name="description" value="{{ $description }}">
                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف القائمة">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- All done banner --}}
    <div class="all-done-banner {{ $allDone ? '' : 'd-none' }}" id="done-banner-{{ $gIdx }}">
        <i class="fas fa-trophy mr-1"></i> تم الانتهاء من الجولة! اضغط "جولة جديدة" للبدء من جديد.
    </div>

    {{-- Entries --}}
    <div class="card-body p-0" id="entries-{{ $gIdx }}">
        @foreach($entries as $entry)
        @php $isDone = $entry->status === 'published'; @endphp
        <div class="q-entry {{ $isDone ? 'q-entry-done' : '' }}"
             id="qe-{{ $entry->id }}"
             data-group="{{ $gIdx }}"
             data-done="{{ $isDone ? '1' : '0' }}">

            {{-- Avatar --}}
            <div class="q-avatar">{{ $entry->player?->initials }}</div>

            {{-- Name + notes --}}
            <div class="q-info">
                <span class="q-name {{ $isDone ? 'q-name-done' : '' }}">
                    {{ $entry->player?->full_name }}
                </span>
                @if($entry->notes)
                    <span class="q-note">{{ $entry->notes }}</span>
                @endif
            </div>

            {{-- Published date (if done) --}}
            @if($isDone && $entry->published_at)
            <span class="q-pub-date">{{ $entry->published_at->format('d/m') }}</span>
            @endif

            {{-- Check button / done badge --}}
            <div class="q-action">
                @if($isDone)
                    <span class="badge badge-success q-done-badge" id="qstatus-{{ $entry->id }}">
                        <i class="fas fa-check"></i>
                    </span>
                @else
                    <button type="button"
                            class="btn btn-sm btn-outline-success mark-done-btn q-check-btn"
                            id="qstatus-{{ $entry->id }}"
                            data-id="{{ $entry->id }}"
                            data-group="{{ $gIdx }}"
                            title="تم النشر">
                        <i class="fas fa-check"></i>
                    </button>
                @endif
            </div>

            {{-- Edit / Delete --}}
            <div class="q-meta-actions">
                <button type="button"
                        class="btn btn-sm btn-link text-muted edit-btn p-0"
                        data-id="{{ $entry->id }}"
                        data-player="{{ $entry->player_id }}"
                        data-description="{{ $entry->custom_description }}"
                        data-notes="{{ $entry->notes }}"
                        title="تعديل">
                    <i class="fas fa-edit"></i>
                </button>
                <form method="POST" action="{{ route('social.destroy', $entry) }}"
                      class="d-inline" onsubmit="return confirm('حذف هذا الطالب من القائمة؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endforeach
@endif

{{-- Add Single Entry Modal --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,var(--accent),var(--accent-dark));border:none;padding:20px 24px">
                <h5 class="modal-title text-white" style="font-size:15px;font-weight:700">
                    <i class="fas fa-user-plus mr-2"></i> إضافة طالب لقائمة
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
                            @foreach(\App\Models\Player::orderBy('full_name')->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>نوع المحتوى <span class="text-danger">*</span></label>
                        <input type="text" name="custom_description" class="form-control" required
                               placeholder="ستوري، ريلز...">
                    </div>
                    <div class="form-group mb-0">
                        <label>ملاحظة (اختياري)</label>
                        <input type="text" name="notes" class="form-control"
                               placeholder="ملاحظة...">
                    </div>
                </div>
                <div class="modal-footer" style="padding:14px 24px;border-top:1px solid var(--border-color)">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none">
            <div class="modal-header" style="background:linear-gradient(135deg,#f59e0b,#d97706);border:none;padding:20px 24px">
                <h5 class="modal-title text-white" style="font-size:15px;font-weight:700">
                    <i class="fas fa-edit mr-2"></i> تعديل
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf @method('PATCH')
                <div class="modal-body" style="padding:24px">
                    <div class="form-group mb-3">
                        <label>الطالب</label>
                        <select name="player_id" id="editPlayer" class="form-control" required>
                            <option value="">اختر...</option>
                            @foreach(\App\Models\Player::orderBy('full_name')->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>نوع المحتوى</label>
                        <input type="text" name="custom_description" id="editDescription" class="form-control" required>
                    </div>
                    <div class="form-group mb-0">
                        <label>ملاحظة</label>
                        <input type="text" name="notes" id="editNotes" class="form-control">
                    </div>
                </div>
                <div class="modal-footer" style="padding:14px 24px;border-top:1px solid var(--border-color)">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
/* ── Description Badge ── */
.desc-badge {
    display: inline-block; padding: 4px 16px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--accent), #1a7a55);
    color: #fff; font-size: 13px; font-weight: 700;
}

/* ── Queue Entry ── */
.q-entry {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 16px;
    border-bottom: 1px solid var(--border-color);
    transition: background .15s;
}
.q-entry:last-child { border-bottom: none; }
.q-entry:hover { background: #f9fafb; }
.q-entry-done { opacity: .55; background: #fafafa; }
.q-entry-done:hover { background: #f3f4f6; }

/* ── Avatar ── */
.q-avatar {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, #0c3c2c, #1a7a55);
    color: #fff; font-size: 12px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.q-entry-done .q-avatar { filter: grayscale(.6); }

/* ── Info ── */
.q-info { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 1px; }
.q-name { font-size: 13.5px; font-weight: 600; color: var(--text-primary); }
.q-name-done { text-decoration: line-through; color: var(--text-muted); }
.q-note { font-size: 11.5px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ── Published date ── */
.q-pub-date { font-size: 11px; color: var(--text-muted); white-space: nowrap; flex-shrink: 0; }

/* ── Check action ── */
.q-action { flex-shrink: 0; }
.q-check-btn { border-radius: 8px; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; }
.q-done-badge { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; }

/* ── Meta actions (edit/delete) ── */
.q-meta-actions { display: flex; gap: 6px; flex-shrink: 0; }
.q-meta-actions .btn { opacity: .4; transition: opacity .15s; }
.q-entry:hover .q-meta-actions .btn { opacity: 1; }

/* ── All done banner ── */
.all-done-banner {
    background: linear-gradient(135deg, #0c3c2c, #1a7a55);
    color: #fff; text-align: center;
    padding: 8px 16px; font-size: 13px; font-weight: 600;
}

/* ── Label sm ── */
.form-label-sm { font-size: 13px; font-weight: 600; margin-bottom: 4px; display: block; }
</style>
@endsection

@section('js')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ── Mark single entry done (AJAX) ── */
$(document).on('click', '.mark-done-btn', function () {
    const btn     = $(this);
    const id      = btn.data('id');
    const groupId = btn.data('group');

    fetch('{{ route("social.markPublished", ":id") }}'.replace(':id', id), {
        method:  'PATCH',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(() => markEntryDone(id, groupId));
});

/* ── Mark ALL pending in group (AJAX) ── */
$(document).on('click', '.mark-all-btn', function () {
    const btn         = $(this);
    const groupId     = btn.data('group');
    const description = btn.data('description');

    fetch('{{ route("social.markAll") }}', {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body:    JSON.stringify({ description }),
    })
    .then(r => r.json())
    .then(() => {
        $(`#entries-${groupId} .q-entry[data-done="0"]`).each(function () {
            markEntryDone(this.id.replace('qe-', ''), groupId, true);
        });
        updateGroupHeader(groupId);
    });
});

/* ── Helper: apply "done" style + move to bottom ── */
function markEntryDone(id, groupId, batch) {
    const $entry   = $(`#qe-${id}`);
    const $list    = $(`#entries-${groupId}`);
    const $btn     = $(`#qstatus-${id}`);

    $entry.addClass('q-entry-done').attr('data-done', '1');
    $entry.find('.q-name').addClass('q-name-done');
    $entry.find('.q-avatar').css('filter', 'grayscale(.6)');
    $btn.replaceWith(`<span class="badge badge-success q-done-badge" id="qstatus-${id}"><i class="fas fa-check"></i></span>`);

    // Move to bottom of the list
    $list.append($entry);

    if (!batch) updateGroupHeader(groupId);
}

/* ── Update pending counter + show/hide "done" banner ── */
function updateGroupHeader(groupId) {
    const total   = $(`#entries-${groupId} .q-entry`).length;
    const pending = $(`#entries-${groupId} .q-entry[data-done="0"]`).length;

    $(`#pending-count-${groupId}`).text(`${pending} متبقي من ${total}`);

    if (pending === 0) {
        $(`#done-banner-${groupId}`).removeClass('d-none');
        $(`#reset-form-${groupId}`).removeClass('d-none');
        $(`[data-group="${groupId}"].mark-all-btn`).addClass('d-none');
    }
}

/* ── Edit modal ── */
$(document).on('click', '.edit-btn', function () {
    const d = this.dataset;
    document.getElementById('editForm').action =
        '{{ route("social.update", ":id") }}'.replace(':id', d.id);
    document.getElementById('editPlayer').value      = d.player;
    document.getElementById('editDescription').value = d.description;
    document.getElementById('editNotes').value        = d.notes || '';
    jQuery('#editModal').modal('show');
});

</script>
@endsection
