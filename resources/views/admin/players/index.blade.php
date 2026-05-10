@extends('layouts.admin')
@section('title', 'اللاعبون')

@section('contentheader', 'إدارة اللاعبين')
@section('contentheaderactive', 'اللاعبون')

@section('content')
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted" style="font-size:13px">
                إجمالي اللاعبين: <strong>{{ $players->total() }}</strong>
            </span>
        </div>
        <a href="{{ route('players.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> إضافة لاعب
        </a>
    </div>
</div>

{{-- Search --}}
<div class="card mb-3">
    <div class="card-body" style="padding:14px 22px !important">
        <form method="GET" action="{{ route('players.index') }}" class="d-flex gap-2" style="gap:10px">
            <input type="text" name="search" class="form-control" style="max-width:320px"
                   placeholder="بحث بالاسم أو الهاتف..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search mr-1"></i> بحث
            </button>
            @if(request('search'))
            <a href="{{ route('players.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-1"></i> إلغاء
            </a>
            @endif
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
                        <th>تاريخ الميلاد / العمر</th>
                        <th>الهاتف</th>
                        <th>الوزن / الطول</th>
                        <th>الاشتراك</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players as $player)
                    <tr>
                        <td class="text-muted" style="font-size:12px">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center" style="gap:12px">
                                @if($player->photo_url)
                                    <img src="{{ $player->photo_url }}" alt="{{ $player->full_name }}"
                                         style="width:42px;height:42px;border-radius:10px;object-fit:cover;box-shadow:0 2px 8px rgba(0,0,0,.1)">
                                @else
                                    <div class="player-avatar">{{ $player->initials }}</div>
                                @endif
                                <div>
                                    <div style="font-weight:600;font-size:14px">{{ $player->full_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($player->birth_date)
                                <div style="font-size:13px">{{ $player->birth_date->format('Y/m/d') }}</div>
                                <div class="text-muted" style="font-size:12px">{{ $player->age }} سنة</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($player->phone)
                                <a href="tel:{{ $player->phone }}" style="font-size:13px;text-decoration:none;color:var(--text-primary)">
                                    <i class="fas fa-phone text-muted mr-1" style="font-size:11px"></i>
                                    {{ $player->phone }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size:13px">
                                @if($player->weight)
                                    <span class="badge badge-secondary mr-1">{{ $player->weight }} kg</span>
                                @endif
                                @if($player->height)
                                    <span class="badge badge-secondary">{{ $player->height }} cm</span>
                                @endif
                                @if(!$player->weight && !$player->height)
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($player->subscription)
                                @php $badge = $player->subscription->status_badge; @endphp
                                <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                <div class="text-muted mt-1" style="font-size:11px">
                                    متبقي: {{ number_format($player->subscription->remaining_amount, 0) }} د.أ
                                </div>
                            @else
                                <span class="badge badge-secondary">لا يوجد</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex" style="gap:6px">
                                <a href="{{ route('players.edit', $player) }}" class="btn btn-sm btn-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('players.destroy', $player) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا اللاعب؟')">
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
                        <td colspan="7" class="text-center py-5">
                            <div style="color:var(--text-muted)">
                                <i class="fas fa-users" style="font-size:36px;opacity:.3;display:block;margin-bottom:10px"></i>
                                لا يوجد لاعبون بعد
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($players->hasPages())
    <div class="card-footer">
        {{ $players->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection

@section('css')
<style>
.player-avatar {
    width: 42px; height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>
@endsection
