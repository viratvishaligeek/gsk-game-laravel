@extends('frontend.include.app')
@section('title', 'Page not found | ' . setting('title', 'GSK'))
@section('meta_description', 'The requested page could not be found. Return to the result board or browse the available markets.')
@section('robots_content', 'noindex,follow')
@section('content')
<section class="gsk-v3-inner-hero gsk-v4-not-found-hero">
  <div class="container py-5 py-lg-6">
    <span class="gsk-v3-kicker"><span class="gsk-v3-live-dot"></span> ERROR 404 · RECORD NOT FOUND</span>
    <h1 class="mt-3">This page <em>isn't here.</em></h1>
    <p>The address may have changed, or the requested market archive may no longer be available. Choose a destination below to continue.</p>
  </div>
</section>
<section class="container py-4 py-lg-5 gsk-v4-not-found">
  <div class="gsk-v4-not-found-panel">
    <div class="gsk-v4-not-found-number" aria-hidden="true">404</div>
    <div>
      <span>BACK TO THE RESULT DESK</span>
      <h2>Where would you like to go?</h2>
      <p>Continue to the live result board, explore historical charts or visit the community.</p>
      <div class="gsk-v4-not-found-actions">
        <a class="btn btn-primary" href="{{ route('index') }}">Return to homepage <span aria-hidden="true">↗</span></a>
        <a class="btn btn-outline-primary" href="{{ route('charts.index') }}">Monthly charts <span aria-hidden="true">↗</span></a>
        <a class="btn btn-outline-primary" href="{{ route('community.index') }}">Community <span aria-hidden="true">↗</span></a>
      </div>
    </div>
  </div>
</section>
@endsection
