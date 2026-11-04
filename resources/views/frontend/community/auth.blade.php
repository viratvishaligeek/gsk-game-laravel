@extends('frontend.include.layout')

@section('meta_title', ($mode === 'register' ? 'Create Community Account' : 'Community Sign In') . ' | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_robots', 'noindex,follow')

@section('content')
<section class="section"><div class="" style="max-width:520px">
    <div class="card shadow-sm"><div class="card-body p-4">
        <h1 class="h3">{{ $mode === 'register' ? 'Create your account' : 'Sign in to the community' }}</h1>
        <p class="text-body-secondary">Participate in moderated community discussions.</p>
        <form method="POST" action="{{ $mode === 'register' ? route('community.register.submit') : route('community.login.submit') }}">
            @csrf
            @if ($mode === 'register')
                <div class="mb-3"><label class="form-label" for="name">Name</label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" minlength="2" maxlength="200" autocomplete="name" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            @endif
            <div class="mb-3"><label class="form-label" for="phone">Phone</label><input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" inputmode="tel" autocomplete="tel" maxlength="15" required>@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="password">Password</label><input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="{{ $mode === 'register' ? 'new-password' : 'current-password' }}" minlength="{{ $mode === 'register' ? 8 : 1 }}" maxlength="200" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            @if ($mode === 'register')
                <div class="mb-3"><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" maxlength="200" required></div>
            @else
                <div class="form-check mb-3"><input class="form-check-input" id="remember" name="remember" type="checkbox" value="1"><label class="form-check-label" for="remember">Remember me</label></div>
            @endif
            <button class="btn btn-primary w-100" type="submit">{{ $mode === 'register' ? 'Create account' : 'Sign in' }}</button>
        </form>
        <p class="mt-3 mb-0 small">@if ($mode === 'register')
                Already have an account? <a href="{{ route('community.login') }}">Sign in</a>
            @else
                New here? <a href="{{ route('community.register') }}">Create an account</a>
            @endif</p>
    </div></div>
</div></section>
@endsection
