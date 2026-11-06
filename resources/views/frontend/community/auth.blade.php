@extends('frontend.include.app')
@section('title', ($mode === 'login' ? 'Member Login' : 'Create Member Account') . ' | ' . setting('title', 'GSK'))
@section('robots_content', 'noindex,follow')
@section('content')
    <section class="v6-auth-layout">
        <section class="v6-auth-story"><span class="v6-eyebrow" style="color:#ffe18d">COMMUNITY MEMBER AREA</span>
            <h1>{{ $mode === 'login' ? 'Welcome back.' : 'Join the conversation.' }}</h1>
            <p>{{ $mode === 'login' ? 'Sign in to reply to discussions and manage your community activity.' : 'Create a member profile to publish discussions and reply to other members.' }}
            </p><a href="{{ route('community.index') }}">Browse community discussions ↗</a>
        </section>
        <section class="v6-auth-panel">
            <div class="v6-form-panel"><span class="v6-eyebrow eyebrow-blue">MEMBER ACCESS</span>
                <h2 class="mt-2">{{ $mode === 'login' ? 'Sign in' : 'Create your account' }}</h2>
                <p class="small text-secondary">This account is for community discussions and replies.</p>
                <form method="POST"
                    action="{{ $mode === 'login' ? route('community.login.submit') : route('community.register.submit') }}">@csrf
                    @if ($mode === 'register')
                        <div class="mb-3"><label class="form-label" for="name">Display name</label><input
                                class="form-control" id="name" name="name" type="text" maxlength="200"
                                minlength="2" value="{{ old('name') }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                    <div class="mb-3"><label class="form-label" for="phone">Registered phone number</label><input
                            class="form-control" id="phone" name="phone" type="tel" autocomplete="tel"
                            value="{{ old('phone') }}" required>
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3"><label class="form-label" for="password">Password</label><input class="form-control"
                            id="password" name="password" type="password"
                            autocomplete="{{ $mode === 'login' ? 'current-password' : 'new-password' }}"
                            minlength="{{ $mode === 'register' ? 8 : 1 }}" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @if ($mode === 'register')
                        <div class="mb-3"><label class="form-label" for="password_confirmation">Confirm
                                password</label><input class="form-control" id="password_confirmation"
                                name="password_confirmation" type="password" autocomplete="new-password" minlength="8"
                                required></div>
                    @endif
                    @if ($mode === 'login')
                        <div class="form-check mb-3"><input class="form-check-input" id="remember" name="remember"
                                type="checkbox" value="1"><label class="form-check-label" for="remember">Remember
                                me</label></div>
                    @endif
                    <button class="v6-btn v6-btn-blue w-100"
                        type="submit">{{ $mode === 'login' ? 'Sign in →' : 'Create member account →' }}</button>
                </form>
                <p class="small mt-4 mb-0">{{ $mode === 'login' ? 'New to the community?' : 'Already a member?' }} <a
                        class="fw-bold"
                        href="{{ $mode === 'login' ? route('community.register') : route('community.login') }}">{{ $mode === 'login' ? 'Create an account' : 'Sign in' }}</a>
                </p>
            </div>
        </section>
    </section>
@endsection
