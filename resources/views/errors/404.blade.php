@extends('frontend.include.app')
@section('title', 'Page not found | ' . setting('title', 'GSK'))
@section('meta_description', 'The requested page could not be found. Return to the result board or browse the available markets.')
@section('robots_content', 'noindex,follow')
@section('content')
<section class="v6-wrap v6-error"><div class="v6-error-panel"><div><div class="v6-error-code">404</div><span class="v6-eyebrow eyebrow-purple">PAGE NOT FOUND</span></div><div><h1>This page took a wrong turn.</h1><p class="text-secondary">The page may have moved, or the address may be incorrect. Let’s get you back to the results.</p><a class="v6-btn v6-btn-blue" href="{{ route('index') }}">Back to homepage ↗</a></div></div></section>

