<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include('frontend.include.meta')
  <meta name="theme-color" content="#3155d9">
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('frontend/css/gsk-vibrant-v6.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/gsk-arcade-v7.css') }}">@yield('style')@stack('schema')
</head><body>
<a class="v6-skip" href="#main-content">Skip to content</a>
@include('frontend.include.header')
<main id="main-content">@yield('content')</main>
@include('frontend.include.footer')
@if (request()->routeIs('index') && isset($homepageSections))@include('frontend.partial.homepage-sections', ['sections' => $homepageSections->where('location', 'after_footer')])@endif
<nav class="v6-mobile-dock" aria-label="Quick navigation"><a href="{{ route('index') }}"><span>⌂</span><small>Home</small></a><a href="{{ route('index') }}#today-results"><span>◉</span><small>Results</small></a><a href="{{ route('index') }}#markets"><span>♧</span><small>Markets</small></a><a href="{{ route('charts.index') }}"><span>▦</span><small>Charts</small></a><a href="{{ route('community.index') }}"><span>☻</span><small>Community</small></a></nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>@yield('script')
</body></html>