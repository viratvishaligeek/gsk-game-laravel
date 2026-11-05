@extends('frontend.include.app')
@section('title', 'Page not found | ' . setting('title', 'GSK'))
@section('meta_description', 'The requested page could not be found. Return to the result board or browse the available markets.')
@section('robots_content', 'noindex,follow')
@section('content')
<main class="v5-error">
  <div class="v5-error-code" aria-hidden="true">404</div>
  <section><span class="v5-kicker">ERROR / RECORD NOT FOUND</span><h1>This page is off the board.</h1><p class="text-secondary lh-lg">The address may have changed, or the requested archive may no longer be available. Choose a destination and continue exploring.</p>
    <div class="d-flex flex-wrap gap-2 mt-4"><a class="v5-button v5-button-gold" href="{{ route('index') }}">Return home ↗</a><a class="v5-button border border-secondary" href="{{ route('charts.index') }}">Monthly charts</a><a class="v5-button border border-secondary" href="{{ route('community.index') }}">Community</a></div>
  </section>
</main>
@endsection
