@extends('backend.include.layout')

@section('page-header')
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4"><div class="page-header-content"><div class="row align-items-center pt-3"><div class="col-auto mb-3"><h1 class="page-header-title"><div class="page-header-icon"><i data-feather="volume-2"></i></div>{{ $pageName }}</h1></div></div></div></div>
</header>
@endsection

@section('content')
<div class="container-fluid px-4">
    @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    <div class="card mb-4"><div class="card-header">Sound asset paths</div><div class="card-body">
        <p class="text-muted">Use a relative path to a public sound asset, for example <code>frontend/sounds/notification.wav</code>. Parent-directory traversal is not allowed.</p>
        <form method="POST" action="{{ route('admin.notification-sounds.update') }}">
            @csrf
            <div class="mb-3"><label class="form-label" for="notification_sound_private">Private notification sound</label><input class="form-control @error('notification_sound_private') is-invalid @enderror" id="notification_sound_private" name="notification_sound_private" value="{{ old('notification_sound_private', $privateSound) }}" maxlength="255"><small class="form-text text-muted">Leave blank if the client should use its configured sound.</small>@error('notification_sound_private')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="notification_sound_public">Public notification sound</label><input class="form-control @error('notification_sound_public') is-invalid @enderror" id="notification_sound_public" name="notification_sound_public" value="{{ old('notification_sound_public', $publicSound) }}" maxlength="255"><small class="form-text text-muted">Leave blank if the client should use its configured sound.</small>@error('notification_sound_public')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <button type="submit" class="btn btn-primary">Save sound settings</button>
        </form>
    </div></div>
</div>
@endsection
