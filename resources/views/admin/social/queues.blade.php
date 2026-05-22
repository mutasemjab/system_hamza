@extends('layouts.admin')

@section('title', __('messages.Social_Media'))

@section('content')
<div class="container-fluid px-4 py-3" dir="rtl">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="page-title mb-0">قوائم السوشيال ميديا</h4>
            <p class="text-muted small mb-0">أدر قوائم النشر لكل تايب محتوى</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('social.schedule') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-calendar-alt me-1"></i> جدول المواعيد
            </a>
            <button class="btn btn-accent btn-sm" data-toggle="modal" data-target="#modalAddQueue">
                <i class="fas fa-plus me-1"></i> قائمة جديدة
            </button>
        </div>
    </div>


    @if($queues->isEmpty())
        <div class="empty-queues-state text-center py-5">
            <i class="fas fa-layer-group fa-3x mb-3" style="color:var(--accent-light)"></i>
            <h5 class="text-muted">لا توجد قوائم بعد</h5>
            <p class="text-muted small">أنشئ قائمتك الأولى عبر زر "قائمة جديدة"</p>
        </div>
    @else

    {{-- Queue Cards --}}
    <div class="queues-grid">
        @foreach($queues as $queue)
        @php
            $pending   = $queue->pendingEntries;
            $published = $queue->publishedEntries;
            $allDone   = $pending->isEmpty() && $published->isNotEmpty();
        @endphp
        <div class="queue-card" id="queue-{{ $queue->id }}">

            {{-- Queue Header --}}
            <div class="queue-header" style="background: {{ $queue->color }}">
                <div class="queue-header-info">
                    <span class="queue-name">{{ $queue->name }}</span>
                    <span class="queue-badge" id="pending-count-{{ $queue->id }}">
                        {{ $pending->count() }} متبقي
                    </span>
                </div>
                <div class="queue-header-actions">
                    <button class="btn-queue-action"
                            title="إضافة طلاب"
                            data-toggle="modal"
                            data-target="#modalAddStudents"
                            data-queue-id="{{ $queue->id }}"
                            data-queue-name="{{ $queue->name }}">
                        <i class="fas fa-user-plus"></i>
                    </button>
                    @if(!$allDone && $pending->isNotEmpty())
                    <button class="btn-queue-action btn-mark-all"
                            title="تحديد الكل كمنشور"
                            data-queue-id="{{ $queue->id }}">
                        <i class="fas fa-check-double"></i>
                    </button>
                    @endif
                    <form action="{{ route('social.queues.destroy', $queue) }}" method="POST"
                          class="d-inline"
                          onsubmit="return confirm('حذف القائمة كاملة؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-queue-action btn-danger-soft" title="حذف القائمة">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Pending Section --}}
            <div class="queue-section" id="pending-section-{{ $queue->id }}">
                @if($pending->isEmpty())
                    <p class="queue-empty-msg" id="no-pending-{{ $queue->id }}">لا يوجد طلاب في الانتظار</p>
                @else
                    @foreach($pending as $entry)
                    <div class="queue-entry" id="entry-{{ $entry->id }}" data-status="pending">
                        <label class="entry-check-label" for="entry-chk-{{ $entry->id }}">
                            <input type="checkbox"
                                   id="entry-chk-{{ $entry->id }}"
                                   class="entry-checkbox"
                                   data-entry-id="{{ $entry->id }}"
                                   data-queue-id="{{ $queue->id }}">
                            <span class="entry-checkmark"></span>
                        </label>
                        <div class="entry-avatar" style="background: {{ $queue->color }}">
                            {{ strtoupper(mb_substr($entry->player->name, 0, 1)) }}
                        </div>
                        <span class="entry-name">{{ $entry->player->name }}</span>
                        <form action="{{ route('social.queues.entries.destroy', $entry) }}" method="POST"
                              class="ms-auto entry-delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-entry-delete" title="إزالة">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                @endif
            </div>

            {{-- Published Section --}}
            @if($published->isNotEmpty())
            <div class="queue-divider">
                <span>تم النشر</span>
            </div>
            <div class="queue-section queue-section-published" id="published-section-{{ $queue->id }}">
                @foreach($published as $entry)
                <div class="queue-entry queue-entry-published" id="entry-{{ $entry->id }}" data-status="published">
                    <label class="entry-check-label" for="entry-chk-{{ $entry->id }}">
                        <input type="checkbox"
                               id="entry-chk-{{ $entry->id }}"
                               class="entry-checkbox"
                               data-entry-id="{{ $entry->id }}"
                               data-queue-id="{{ $queue->id }}"
                               checked>
                        <span class="entry-checkmark"></span>
                    </label>
                    <div class="entry-avatar entry-avatar-done">
                        {{ strtoupper(mb_substr($entry->player->name, 0, 1)) }}
                    </div>
                    <span class="entry-name entry-name-done">{{ $entry->player->name }}</span>
                    <span class="entry-date ms-auto">{{ $entry->published_at?->format('d/m') }}</span>
                </div>
                @endforeach
            </div>
            @else
                <div class="queue-section queue-section-published d-none" id="published-section-{{ $queue->id }}"></div>
                <div class="queue-divider d-none" id="divider-{{ $queue->id }}"><span>تم النشر</span></div>
            @endif

            {{-- All Done Banner --}}
            @if($allDone)
            <div class="all-done-banner" id="done-banner-{{ $queue->id }}">
                <i class="fas fa-trophy me-1"></i> تم الانتهاء من الجولة!
                <form action="{{ route('social.queues.reset', $queue) }}" method="POST" class="d-inline ms-2">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-new-round">جولة جديدة</button>
                </form>
            </div>
            @else
            <div class="all-done-banner d-none" id="done-banner-{{ $queue->id }}">
                <i class="fas fa-trophy me-1"></i> تم الانتهاء من الجولة!
                <form action="{{ route('social.queues.reset', $queue) }}" method="POST" class="d-inline ms-2">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-new-round">جولة جديدة</button>
                </form>
            </div>
            @endif

        </div>
        @endforeach
    </div>

    @endif
</div>

{{-- ── Modal: Add Queue ───────────────────────────────────────── --}}
<div class="modal fade" id="modalAddQueue" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-card">
            <div class="modal-header modal-header-accent">
                <h5 class="modal-title">قائمة جديدة</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('social.queues.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label-custom">اسم القائمة</label>
                        <input type="text" name="name" class="form-control form-input-custom"
                               placeholder="مثال: ستوري، ريلز، بوست..." required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-accent btn-sm">إنشاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Modal: Add Students to Queue ──────────────────────────── --}}
<div class="modal fade" id="modalAddStudents" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-card">
            <div class="modal-header modal-header-accent">
                <h5 class="modal-title">إضافة طلاب إلى <span id="modal-queue-name"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formAddStudents" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label-custom">اختر الطلاب</label>
                        <select name="player_ids[]" id="selectStudents"
                                class="form-control" multiple required>
                            @foreach($players as $player)
                                <option value="{{ $player->id }}">{{ $player->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-accent btn-sm">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ── Queue Grid ──────────────────────────────────────────────── */
.queues-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.25rem;
    align-items: start;
}

/* ── Queue Card ─────────────────────────────────────────────── */
.queue-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.queue-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .75rem 1rem;
    color: #fff;
    gap: .5rem;
}
.queue-header-info { display: flex; align-items: center; gap: .6rem; flex: 1; min-width: 0; }
.queue-name { font-weight: 700; font-size: .95rem; }
.queue-badge {
    background: rgba(255,255,255,.22);
    border-radius: 20px;
    padding: .15rem .55rem;
    font-size: .72rem;
    white-space: nowrap;
}
.queue-header-actions { display: flex; gap: .35rem; flex-shrink: 0; }

.btn-queue-action {
    background: rgba(255,255,255,.18);
    border: none;
    color: #fff;
    width: 28px; height: 28px;
    border-radius: 7px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem;
    transition: background .2s;
}
.btn-queue-action:hover { background: rgba(255,255,255,.32); }
.btn-queue-action.btn-danger-soft:hover { background: rgba(239,68,68,.6); }

/* ── Section & Entries ─────────────────────────────────────── */
.queue-section { padding: .5rem .75rem; }
.queue-section-published { background: #f9fafb; }

.queue-empty-msg {
    text-align: center;
    color: #aaa;
    font-size: .82rem;
    padding: .75rem 0;
    margin: 0;
}

.queue-entry {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .45rem .4rem;
    border-radius: 8px;
    transition: background .15s;
}
.queue-entry:hover { background: #f0fdf4; }
.queue-entry-published { opacity: .7; }

/* Custom checkbox */
.entry-check-label {
    position: relative;
    cursor: pointer;
    flex-shrink: 0;
    margin: 0;
}
.entry-check-label input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0; height: 0;
}
.entry-checkmark {
    display: block;
    width: 20px; height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 6px;
    transition: all .2s;
    background: #fff;
}
.entry-check-label input:checked ~ .entry-checkmark {
    background: #0c3c2c;
    border-color: #0c3c2c;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M13.485 1.431a1.473 1.473 0 0 0-2.084 0l-6.71 6.68-2.021-2.015a1.473 1.473 0 0 0-2.083 2.083l3.061 3.057a1.473 1.473 0 0 0 2.084 0l7.752-7.72a1.473 1.473 0 0 0 0-2.085z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    background-size: 11px;
}

.entry-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .78rem;
    flex-shrink: 0;
}
.entry-avatar-done { filter: grayscale(.5); }

.entry-name { font-size: .88rem; color: #374151; }
.entry-name-done { text-decoration: line-through; color: #9ca3af; }
.entry-date { font-size: .75rem; color: #9ca3af; }

.btn-entry-delete {
    background: none; border: none;
    color: #d1d5db; font-size: .78rem;
    cursor: pointer; padding: 0 .2rem;
    transition: color .2s;
}
.btn-entry-delete:hover { color: #ef4444; }
.entry-delete-form { margin: 0; }

/* ── Divider ────────────────────────────────────────────────── */
.queue-divider {
    display: flex; align-items: center;
    padding: 0 .75rem;
    margin: .25rem 0;
    gap: .5rem;
    color: #9ca3af; font-size: .72rem;
}
.queue-divider::before, .queue-divider::after {
    content: ''; flex: 1; height: 1px; background: #e5e7eb;
}

/* ── All Done Banner ────────────────────────────────────────── */
.all-done-banner {
    background: linear-gradient(135deg, #0c3c2c, #1a7a55);
    color: #fff;
    text-align: center;
    padding: .65rem;
    font-size: .85rem;
    font-weight: 600;
}
.btn-new-round {
    background: rgba(255,255,255,.22);
    border: 1px solid rgba(255,255,255,.4);
    color: #fff;
    border-radius: 20px;
    padding: .15rem .75rem;
    font-size: .78rem;
    cursor: pointer;
    transition: background .2s;
}
.btn-new-round:hover { background: rgba(255,255,255,.35); }

/* ── Page title ─────────────────────────────────────────────── */
.page-title { font-size: 1.35rem; font-weight: 700; color: #0c3c2c; }

/* ── Utilities ─────────────────────────────────────────────── */
.gap-2 { gap: .5rem; }
.ms-auto { margin-inline-start: auto; }
.ms-2 { margin-inline-start: .5rem; }
.me-1 { margin-inline-end: .25rem; }
.me-3 { margin-inline-end: .75rem; }
</style>
@endpush

@push('scripts')
<script>
$(function () {
    // ── Initialize Select2 for student picker ─────────────────
    $('#selectStudents').select2({
        dropdownParent: $('#modalAddStudents'),
        placeholder:    'ابحث عن طالب...',
        dir:            'rtl',
        width:          '100%',
    });

    // ── Wire up "Add Students" modal: set queue id in action ──
    $('#modalAddStudents').on('show.bs.modal', function (e) {
        const btn     = $(e.relatedTarget);
        const queueId = btn.data('queue-id');
        const name    = btn.data('queue-name');
        $('#modal-queue-name').text(name);
        $('#formAddStudents').attr('action', `/{{ app()->getLocale() }}/admin/social/queues/${queueId}/entries`);
        $('#selectStudents').val(null).trigger('change');
    });

    // ── CSRF token for AJAX ───────────────────────────────────
    const csrf = $('meta[name="csrf-token"]').attr('content');

    // ── Toggle entry via checkbox (AJAX) ─────────────────────
    $(document).on('change', '.entry-checkbox', function () {
        const entryId = $(this).data('entry-id');
        const queueId = $(this).data('queue-id');
        const chk     = this;
        const $entry  = $(`#entry-${entryId}`);

        fetch(`/{{ app()->getLocale() }}/admin/social/queues/entries/${entryId}/toggle`, {
            method:  'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'published') {
                moveToPublished($entry, queueId, data.published_at);
            } else {
                moveToPending($entry, queueId);
            }
            updatePendingCount(queueId);
            checkAllDone(queueId);
        })
        .catch(() => { chk.checked = !chk.checked; }); // revert on error
    });

    // ── Mark All Done (AJAX) ──────────────────────────────────
    $(document).on('click', '.btn-mark-all', function () {
        const queueId = $(this).data('queue-id');

        fetch(`/{{ app()->getLocale() }}/admin/social/queues/${queueId}/mark-all-done`, {
            method:  'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(() => {
            const $pending = $(`#pending-section-${queueId} .queue-entry[data-status="pending"]`);
            $pending.each(function () {
                moveToPublished($(this), queueId, new Date().toLocaleDateString('en-GB', {day:'2-digit',month:'2-digit'}));
            });
            updatePendingCount(queueId);
            checkAllDone(queueId);
        });
    });

    // ── Move entry DOM to Published section ───────────────────
    function moveToPublished($entry, queueId, publishedAt) {
        const date   = publishedAt ? publishedAt.slice(5).split('-').reverse().join('/') : '';
        const $chk   = $entry.find('.entry-checkbox');
        const $name  = $entry.find('.entry-name');
        const $del   = $entry.find('.entry-delete-form');

        $chk.prop('checked', true);
        $entry.attr('data-status', 'published').addClass('queue-entry-published');
        $entry.find('.entry-avatar').addClass('entry-avatar-done');
        $name.addClass('entry-name-done');
        $del.remove();

        // Add date badge
        if (!$entry.find('.entry-date').length) {
            $entry.append(`<span class="entry-date ms-auto">${date}</span>`);
        }

        // Ensure divider & published section visible
        const $divider  = $(`#divider-${queueId}`);
        const $pubSec   = $(`#published-section-${queueId}`);
        $divider.removeClass('d-none');
        $pubSec.removeClass('d-none');
        $pubSec.append($entry);

        $(`#no-pending-${queueId}`).remove();
    }

    // ── Move entry DOM to Pending section ────────────────────
    function moveToPending($entry, queueId) {
        const $chk  = $entry.find('.entry-checkbox');
        const $name = $entry.find('.entry-name');

        $chk.prop('checked', false);
        $entry.attr('data-status', 'pending').removeClass('queue-entry-published');
        $entry.find('.entry-avatar').removeClass('entry-avatar-done');
        $name.removeClass('entry-name-done');
        $entry.find('.entry-date').remove();

        // Append a delete button back
        const entryId = $chk.data('entry-id');
        const csrf2   = $('meta[name="csrf-token"]').attr('content');
        $entry.append(`
            <form action="/{{ app()->getLocale() }}/admin/social/queues/entries/${entryId}" method="POST" class="ms-auto entry-delete-form">
                <input type="hidden" name="_token" value="${csrf2}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn-entry-delete" title="إزالة"><i class="fas fa-times"></i></button>
            </form>`);

        $(`#pending-section-${queueId}`).append($entry);
    }

    // ── Update pending counter in queue header ────────────────
    function updatePendingCount(queueId) {
        const count = $(`#pending-section-${queueId} .queue-entry[data-status="pending"]`).length;
        $(`#pending-count-${queueId}`).text(`${count} متبقي`);
    }

    // ── Check if all entries published → show done banner ────
    function checkAllDone(queueId) {
        const pending = $(`#pending-section-${queueId} .queue-entry[data-status="pending"]`).length;
        const $banner = $(`#done-banner-${queueId}`);

        if (pending === 0) {
            $banner.removeClass('d-none');
            if (!$(`#no-pending-${queueId}`).length) {
                $(`#pending-section-${queueId}`).append(
                    `<p class="queue-empty-msg" id="no-pending-${queueId}">لا يوجد طلاب في الانتظار</p>`
                );
            }
        } else {
            $banner.addClass('d-none');
        }
    }
});
</script>
@endpush
