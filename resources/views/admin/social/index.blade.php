@extends('layouts.admin')
@section('title', 'Ø§Ù„Ø³ÙˆØ´Ø§Ù„ Ù…ÙŠØ¯ÙŠØ§')

@section('contentheader', 'Ø¥Ø¯Ø§Ø±Ø© Ù…Ø­ØªÙˆÙ‰ Ø§Ù„Ø³ÙˆØ´Ø§Ù„ Ù…ÙŠØ¯ÙŠØ§')
@section('contentheaderactive', 'Ø§Ù„Ø³ÙˆØ´Ø§Ù„ Ù…ÙŠØ¯ÙŠØ§')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0" style="font-size:13px">
        <i class="fas fa-info-circle mr-1"></i>
        ØªØªØ¨Ø¹ Ø¯ÙˆØ± ÙƒÙ„ Ù„Ø§Ø¹Ø¨ ÙÙŠ ÙƒÙ„ Ù†ÙˆØ¹ Ù…Ù† Ø£Ù†ÙˆØ§Ø¹ Ø§Ù„Ù…Ø­ØªÙˆÙ‰
    </p>
    <div class="d-flex" style="gap:8px">
        <a href="{{ route('social.schedule') }}" class="btn btn-secondary">
            <i class="fas fa-calendar-alt mr-2"></i> Ø§Ù„Ø¬Ø¯ÙˆÙ„ Ø§Ù„Ø²Ù…Ù†ÙŠ
        </a>
        <a href="{{ route('social.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> Ø¥Ø¶Ø§ÙØ© Ù„Ø§Ø¹Ø¨ Ù„Ù„Ù‚Ø§Ø¦Ù…Ø©
        </a>
    </div>
</div>

{{-- Board --}}
<div class="social-board">
    @foreach($board as $type => $data)
    @php
        $meta      = $data['meta'];
        $current   = $data['current'];
        $queue     = $data['queue'];
        $published = $data['published'];
    @endphp
    <div class="social-col">

        {{-- Column Header --}}
        <div class="social-col-header" style="--col-color: {{ $meta['color'] }}">
            <div class="social-col-icon">
                <i class="{{ $meta['icon'] }}"></i>
            </div>
            <div>
                <div class="social-col-title">{{ $meta['label'] }}</div>
                <div class="social-col-count">
                    {{ $queue->count() }} ÙÙŠ Ø§Ù„Ø§Ù†ØªØ¸Ø§Ø± Â· {{ $published->count() }} Ù…Ù†Ø´ÙˆØ±
                </div>
            </div>
        </div>

        {{-- Current Turn --}}
        <div class="social-section-label">
            <i class="fas fa-star mr-1" style="color:var(--warning)"></i> Ø¯ÙˆØ±Ù‡ Ø§Ù„Ø¢Ù†
        </div>
        @if($current)
        <div class="social-current-card">
            <div class="social-player-row">
                <div class="social-avatar" style="background:linear-gradient(135deg,{{ $meta['color'] }},#1a7a55)">
                    {{ $current->player?->initials }}
                </div>
                <div class="flex-1">
                    <div class="social-player-name">{{ $current->player?->full_name }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">
                        {{ $current->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            <div class="d-flex mt-3" style="gap:6px">
                <form method="POST" action="{{ route('social.markPublished', $current) }}" style="flex:1">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-check mr-1"></i> ØªÙ… Ø§Ù„Ù†Ø´Ø±
                    </button>
                </form>
                <a href="{{ route('social.edit', $current) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="{{ route('social.destroy', $current) }}"
                      onsubmit="return confirm('Ø­Ø°Ù Ù‡Ø°Ø§ Ø§Ù„Ø³Ø¬Ù„ØŸ')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="social-empty-slot">
            <i class="fas fa-user-clock" style="font-size:20px;opacity:.3"></i>
            <div style="font-size:12px;margin-top:6px">Ù„Ø§ ÙŠÙˆØ¬Ø¯ Ø¯ÙˆØ± Ù…Ø­Ø¯Ø¯</div>
        </div>
        @endif

        {{-- Queue --}}
        @if($queue->count())
        <div class="social-section-label mt-3">
            <i class="fas fa-list-ol mr-1" style="color:var(--accent)"></i> Ù‚Ø§Ø¦Ù…Ø© Ø§Ù„Ø§Ù†ØªØ¸Ø§Ø±
        </div>
        <div class="social-queue">
            @foreach($queue as $i => $item)
            <div class="social-queue-item">
                <span class="social-queue-num">{{ $i + 1 }}</span>
                <div class="social-avatar-xs" style="background:linear-gradient(135deg,#0c3c2c,#1a7a55)">
                    {{ $item->player?->initials }}
                </div>
                <span class="flex-1" style="font-size:13px;font-weight:500">{{ $item->player?->full_name }}</span>
                <div class="d-flex" style="gap:4px">
                    <a href="{{ route('social.edit', $item) }}" class="social-queue-action" title="ØªØ¹Ø¯ÙŠÙ„">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('social.destroy', $item) }}"
                          onsubmit="return confirm('Ø­Ø°ÙØŸ')" style="margin:0">
                        @csrf @method('DELETE')
                        <button type="submit" class="social-queue-action text-danger" title="Ø­Ø°Ù">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Published --}}
        @if($published->count())
        <div class="social-section-label mt-3">
            <i class="fas fa-check-double mr-1" style="color:var(--success)"></i> Ø¢Ø®Ø± Ù…Ù†Ø´ÙˆØ±
        </div>
        <div class="social-published">
            @foreach($published as $item)
            <div class="social-published-item">
                <div class="social-avatar-xs" style="background:#e2e8f0;color:var(--text-muted)">
                    {{ $item->player?->initials }}
                </div>
                <span style="font-size:12px;color:var(--text-muted);flex:1">{{ $item->player?->full_name }}</span>
                <span style="font-size:11px;color:var(--success)">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ $item->published_at ? $item->published_at->format('m/d') : 'â€”' }}
                </span>
            </div>
            @endforeach
        </div>
        @endif

    </div>
    @endforeach
</div>

{{-- Upcoming Scheduled Posts --}}
@if($scheduledUpcoming->count())
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-calendar-check text-accent mr-2"></i>
            <span class="card-title">Ø§Ù„Ø¬Ù„Ø³Ø§Øª Ø§Ù„Ù…Ø¬Ø¯ÙˆÙ„Ø© Ø§Ù„Ù‚Ø§Ø¯Ù…Ø©</span>
            <span class="badge badge-primary ml-2">{{ $scheduledUpcoming->count() }}</span>
        </div>
        <a href="{{ route('social.schedule') }}" class="btn btn-secondary btn-sm">
            Ø¹Ø±Ø¶ Ø§Ù„ÙƒÙ„ <i class="fas fa-arrow-left ml-1"></i>
        </a>
    </div>
    <div class="card-body" style="padding:0 !important">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Ø§Ù„ØªØ§Ø±ÙŠØ®</th>
                        <th>Ø§Ù„Ø·Ø§Ù„Ø¨</th>
                        <th>Ø§Ù„ÙˆØµÙ</th>
                        <th>Ø§Ù„Ø­Ø§Ù„Ø©</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $arabicDays = ['Ø§Ù„Ø£Ø­Ø¯','Ø§Ù„Ø§Ø«Ù†ÙŠÙ†','Ø§Ù„Ø«Ù„Ø§Ø«Ø§Ø¡','Ø§Ù„Ø£Ø±Ø¨Ø¹Ø§Ø¡','Ø§Ù„Ø®Ù…ÙŠØ³','Ø§Ù„Ø¬Ù…Ø¹Ø©','Ø§Ù„Ø³Ø¨Øª'];
                    @endphp
                    @foreach($scheduledUpcoming as $item)
                    <tr>
                        <td style="font-weight:600;font-size:13px;white-space:nowrap">
                            {{ $item->scheduled_date?->format('Y/m/d') }}
                            <div style="font-size:11px;color:var(--text-muted)">
                                {{ $item->scheduled_date ? $arabicDays[$item->scheduled_date->dayOfWeek] : '' }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:8px">
                                <div class="player-avatar-sm">{{ $item->player?->initials }}</div>
                                <span style="font-size:13px;font-weight:600">{{ $item->player?->full_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $item->custom_description }}</span>
                        </td>
                        <td>
                            @if($item->scheduled_date && $item->scheduled_date->isToday())
                                <span class="badge badge-success">Ø§Ù„ÙŠÙˆÙ…</span>
                            @elseif($item->scheduled_date && $item->scheduled_date->isTomorrow())
                                <span class="badge badge-primary">Ø¨ÙƒØ±Ø§</span>
                            @elseif($item->scheduled_date && $item->scheduled_date->isPast())
                                <span class="badge badge-danger">Ù…ØªØ£Ø®Ø±</span>
                            @else
                                <span class="badge badge-secondary">Ù‚Ø§Ø¯Ù…</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('social.markPublished', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="ØªÙ… Ø§Ù„Ù†Ø´Ø±">
                                    <i class="fas fa-check mr-1"></i> ØªÙ…
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@section('css')
<style>
/* Board Layout */
.social-board {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    align-items: start;
}
@media (max-width: 1199px) { .social-board { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 767px)  { .social-board { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .social-board { grid-template-columns: 1fr; } }

.social-col {
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--border-color);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* Column header */
.social-col-header {
    padding: 16px;
    background: linear-gradient(135deg, var(--col-color, #0c3c2c), color-mix(in srgb, var(--col-color, #0c3c2c) 70%, #000));
    display: flex;
    align-items: center;
    gap: 12px;
}
.social-col-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 18px; flex-shrink: 0;
}
.social-col-title {
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.2;
}
.social-col-count {
    color: rgba(255,255,255,.75);
    font-size: 11px;
    margin-top: 2px;
}

/* Section labels */
.social-section-label {
    padding: 10px 14px 4px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: var(--text-muted);
}

/* Current turn card */
.social-current-card {
    margin: 0 12px 4px;
    padding: 14px;
    background: linear-gradient(135deg, rgba(99,102,241,.06), rgba(139,92,246,.04));
    border: 1px solid rgba(99,102,241,.2);
    border-radius: 12px;
}
.social-player-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.social-avatar {
    width: 40px; height: 40px;
    border-radius: 10px;
    color: #fff; font-size: 14px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(0,0,0,.15);
}
.social-avatar-xs {
    width: 28px; height: 28px;
    border-radius: 7px;
    color: #fff; font-size: 10px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.social-player-name { font-size: 13px; font-weight: 700; color: var(--text-primary); }

/* Empty slot */
.social-empty-slot {
    margin: 0 12px 4px;
    padding: 20px;
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    text-align: center;
    color: var(--text-muted);
}

/* Queue */
.social-queue {
    padding: 0 12px 4px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.social-queue-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    background: #f8fafc;
    border-radius: 8px;
    transition: background .15s;
}
.social-queue-item:hover { background: #f0f4ff; }
.social-queue-num {
    width: 20px; height: 20px;
    border-radius: 6px;
    background: var(--border-color);
    color: var(--text-muted);
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.social-queue-action {
    width: 26px; height: 26px;
    border-radius: 6px;
    background: none; border: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-muted);
    font-size: 11px;
    transition: background .15s, color .15s;
}
.social-queue-action:hover { background: #e2e8f0; color: var(--text-primary); }

/* Published */
.social-published {
    padding: 0 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.social-published-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 0;
    border-bottom: 1px solid #f1f5f9;
}
.social-published-item:last-child { border-bottom: none; }
.flex-1 { flex: 1; min-width: 0; }
.w-100 { width: 100% !important; }
</style>
@endsection
