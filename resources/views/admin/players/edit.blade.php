@extends('layouts.admin')
@section('title', 'تعديل لاعب')

@section('contentheader', 'تعديل بيانات اللاعب')
@section('contentheaderactive', 'تعديل')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('players.update', $player) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-edit text-accent mr-2"></i>
                    <span class="card-title">{{ $player->full_name }}</span>
                </div>
                <div class="card-body">

                    {{-- Photo --}}
                    <div class="text-center mb-4">
                        <div id="photoPreviewWrap" style="display:inline-block">
                            @if($player->photo_url)
                                <img id="photoPreview" src="{{ $player->photo_url }}" alt="{{ $player->full_name }}"
                                     style="width:100px;height:100px;border-radius:16px;object-fit:cover;box-shadow:0 4px 16px rgba(0,0,0,.12)">
                                <div id="photoPlaceholder" class="upload-avatar-placeholder" style="display:none">
                                    <i class="fas fa-camera" style="font-size:22px;color:#94a3b8"></i>
                                </div>
                            @else
                                <div id="photoPlaceholder" class="upload-avatar-placeholder">
                                    <i class="fas fa-camera" style="font-size:22px;color:#94a3b8"></i>
                                    <div style="font-size:12px;color:#94a3b8;margin-top:6px">صورة اللاعب</div>
                                </div>
                                <img id="photoPreview" src="" alt="" style="display:none;width:100px;height:100px;border-radius:16px;object-fit:cover">
                            @endif
                        </div>
                        <div class="mt-2">
                            <label for="photo" class="btn btn-secondary btn-sm" style="cursor:pointer;display:inline-block">
                                <i class="fas fa-upload mr-1"></i> تغيير الصورة
                            </label>
                            <input type="file" id="photo" name="photo" accept="image/*" class="d-none">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                                       value="{{ old('full_name', $player->full_name) }}">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>رقم الهاتف</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $player->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>تاريخ الميلاد</label>
                                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                                       value="{{ old('birth_date', $player->birth_date?->format('Y-m-d')) }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>الوزن (kg)</label>
                                <input type="number" name="weight" step="0.1" class="form-control @error('weight') is-invalid @enderror"
                                       value="{{ old('weight', $player->weight) }}">
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>الطول (cm)</label>
                                <input type="number" name="height" step="0.1" class="form-control @error('height') is-invalid @enderror"
                                       value="{{ old('height', $player->height) }}">
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label>ملاحظات</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                          rows="3">{{ old('notes', $player->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('players.index') }}" class="btn btn-secondary">
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
.upload-avatar-placeholder {
    width: 100px; height: 100px;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    background: #f8fafc; cursor: pointer;
    transition: border-color .2s;
}
</style>
@endsection

@section('js')
<script>
document.getElementById('photo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoPlaceholder').style.display = 'none';
        const img = document.getElementById('photoPreview');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
