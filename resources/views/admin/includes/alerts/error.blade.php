@if(Session::has('error'))
<div class="alert-toast alert-toast-danger" id="errorToast">
    <div class="alert-toast-icon"><i class="fas fa-exclamation-circle"></i></div>
    <div class="alert-toast-body">{{ Session::get('error') }}</div>
    <button class="alert-toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif
@foreach($errors->all() as $message)
<div class="alert-toast alert-toast-danger">
    <div class="alert-toast-icon"><i class="fas fa-exclamation-circle"></i></div>
    <div class="alert-toast-body">{{ $message }}</div>
    <button class="alert-toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
@endforeach
