@extends('frontend.include.app')
@section('title', $title . ' | ' . setting('title', 'gali satta kings'))
@section('meta_description', \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($meta_description))), 160))
@section('structured_data')
    @php
        $pageCanonical =
            rtrim((string) setting('canonical_url', 'https://galisattakings.com'), '/') . request()->getPathInfo();
        $pageDescription =
            $page->meta_description ?:
            \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $page->content))), 160);
        $pageSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->meta_title ?: $page->name,
            'url' => $pageCanonical,
            'description' => $pageDescription,
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => rtrim((string) setting('canonical_url', 'https://galisattakings.com'), '/') . '/',
                    ],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $page->name, 'item' => $pageCanonical],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">@json($pageSchema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>
@endsection
@section('content')
<section class="gsk-v3-inner-hero"><div class="container py-4 py-lg-5">
  <nav aria-label="Breadcrumb"><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li><li class="breadcrumb-item active" aria-current="page">{{ $page->name }}</li></ol></nav>
  <span class="gsk-v3-kicker"><span class="gsk-v3-live-dot"></span> PUBLISHED INFORMATION</span>
  <h1 class="mt-3">{{ $page->name }}</h1>
  @if (filled($meta_description))<p>{{ $meta_description }}</p>@endif
  <div class="gsk-v3-inner-links"><a href="{{ route('index') }}#today-results">🏆 Latest results</a><a href="{{ route('charts.index') }}">📊 Result charts</a><a href="{{ route('community.index') }}">👥 Community</a></div>
</div></section>
<section class="container py-4 py-lg-5 gsk-v3-document-layout">
  <article class="gsk-v3-document-card">
    <div class="gsk-v3-document-kicker">📘 GSK INFORMATION CENTER <span>·</span> {{ $page->name }}</div>
    <div class="gsk-information-copy">{!! $page->content !!}</div>
    <a class="btn btn-outline-primary rounded-pill mt-4" href="{{ route('index') }}">← Back to home</a>
  </article>
</section>
@endsectionn
