@extends('frontend.include.app')
@section('title', ($mode === 'login' ? 'Member Login' : 'Create Member Account') . ' | ' . setting('title', 'GSK'))
@section('robots_content', 'noindex,follow')
@section('content')
<section class="gsk-page-hero"><div class="container py-4 py-lg-5"><span class="gsk-kicker">👥 COMMUNITY ACCESS</span><h1 class="mt-3">{{ $mode === 'login' ? 'Welcome back to the community' : 'Join the community' }}</h1><p class="mb-0">Use your existing member account or create one to take part in discussions.</p></div></section>
<section class="container py-4 py-lg-5"><div class="gsk-community-form-card mx-auto">
  <div class="gsk-section-heading"><div><span>MEMBER ACCOUNT</span><h2>{{ $mode === 'login' ? 'Sign in' : 'Create account' }}</h2></div></div>
  <form method="POST" action="{{ $mode === 'login' ? route('community.login.submit') : route('community.register.submit') }}">@csrf
    @if ($mode === 'register')<div class="mb-3"><label class="form-label fw-semibold" for="name">Display name</label><input class="form-control" id="name" name="name" type="text" maxlength="200" minlength="2" value="{{ old('name') }}" required>@error('name')<small class="text-danger">{{ $message }}</small>@enderror</div>@endif
    <div class="mb-3"><label class="form-label fw-semibold" for="phone">Registered phone number</label><input class="form-control" id="phone" name="phone" type="tel" autocomplete="tel" value="{{ old('phone') }}" required>@error('phone')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <div class="mb-3"><label class="form-label fw-semibold" for="password">Password</label><input class="form-control" id="password" name="password" type="password" autocomplete="{{ $mode === 'login' ? 'current-password' : 'new-password' }}" minlength="{{ $mode === 'register' ? 8 : 1 }}" required>@error('password')<small class="text-danger">{{ $message }}</small>@enderror</div>
    @if ($mode === 'register')<div class="mb-3"><label class="form-label fw-semibold" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required></div>@endif
    @if ($mode === 'login')<div class="form-check mb-3"><input class="form-check-input" id="remember" name="remember" type="checkbox" value="1"><label class="form-check-label" for="remember">Remember me</label></div>@endif
    <button class="btn btn-primary w-100 rounded-pill" type="submit">{{ $mode === 'login' ? 'Sign in →' : 'Create member account →' }}</button>
  </form>
  <p class="small text-secondary mt-3 mb-0">{{ $mode === 'login' ? 'New to the community?' : 'Already a member?' }} <a class="fw-bold" href="{{ $mode === 'login' ? route('community.register') : route('community.login') }}">{{ $mode === 'login' ? 'Create an account' : 'Sign in' }}</a></p>
</div></section>
@endsection
