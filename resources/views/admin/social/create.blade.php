@extends('layouts.admin')
@section('title', 'إضافة محتوى')

@section('contentheader', 'إضافة لاعب لقائمة المحتوى')
@section('contentheaderactive', 'إضافة')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <form method="POST" action="{{ route('admin.social.store') }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-plus text-accent mr-2"></i>
                    <span class="card-title">إضافة لاعب لجدول المحتوى</span>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
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
                    </div>

                    <div class="form-group mb-3">
                        <label>نوع المحتوى <span class="text-danger">*</span></label>
                        <div class="type-grid">
                            @foreach($types as $key => $meta)
                            <label class="type-option {{ old('content_type') == $key ? 'selected' : '' }}"
                                   style="--type-color: {{ $meta['color'] }}">
                                <input type="radio" name="content_type" value="{{ $key }}"
                                       {{ old('content_type') == $key ? 'checked' : '' }}
                                       class="d-none">
                                <div class="type-icon">
                                    <i class="{{ $meta['icon'] }}"></i>
                                </div>
                                <div style="font-size:12px;font-weight:600;margin-top:6px">{{ $meta['label'] }}</div>
                            </label>
                            @endforeach
                        </div>
                        @error('content_type')
                            <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>الحالة <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach(['pending' => ['label' => 'في الانتظار', 'icon' => 'fa-clock', 'color' => 'var(--accent)'], 'next' => ['label' => 'دوره الآن', 'icon' => 'fa-star', 'color' => 'var(--warning)'], 'published' => ['label' => 'منشور', 'icon' => 'fa-check-circle', 'color' => 'var(--success)']] as $val => $opt)
                            <div class="col-4">
                                <label class="status-option {{ old('status', 'pending') == $val ? 'selected' : '' }}"
                                       style="--s-color: {{ $opt['color'] }}">
                                    <input type="radio" name="status" value="{{ $val }}"
                                           {{ old('status', 'pending') == $val ? 'checked' : '' }}
                                           class="d-none">
                                    <i class="fas {{ $opt['icon'] }}" style="font-size:18px"></i>
                                    <div style="font-size:12px;font-weight:600;margin-top:4px">{{ $opt['label'] }}</div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('status')
                            <div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label>ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="2"
                                  placeholder="أي ملاحظات...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.social.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right mr-1"></i> رجوع
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> إضافة للقائمة
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
<style>
.type-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}
@media (max-width: 576px) { .type-grid { grid-template-columns: repeat(3, 1fr); } }

.type-option {
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 14px 8px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    color: var(--text-muted);
}
.type-option:hover, .type-option.selected {
    border-color: var(--type-color, var(--accent));
    background: color-mix(in srgb, var(--type-color, var(--accent)) 8%, white);
    color: var(--type-color, var(--accent));
}
.type-icon { font-size: 24px; }

.status-option {
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 14px 8px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    color: var(--text-muted);
    display: block;
}
.status-option:hover, .status-option.selected {
    border-color: var(--s-color, var(--accent));
    background: color-mix(in srgb, var(--s-color, var(--accent)) 8%, white);
    color: var(--s-color, var(--accent));
}
</style>
@endsection

@section('js')
<script>
// Type selection visual feedback
document.querySelectorAll('input[name="content_type"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.type-option').forEach(el => el.classList.remove('selected'));
        radio.closest('.type-option').classList.add('selected');
    });
});
// Status selection visual feedback
document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.status-option').forEach(el => el.classList.remove('selected'));
        radio.closest('.status-option').classList.add('selected');
    });
});
</script>
@endsection
