@extends('frontend.include.layout')

@section('meta_title', $title . ' | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($copy), 155))
@section('meta_robots', $page === 'faq' ? 'index,follow' : 'index,follow')

@section('content')
<section class="section"><div class="" style="max-width:900px">
    <article class="blog-card">
        <h1>{{ $title }}</h1>
        <p class="muted">{{ $copy }}</p>
    </article>
    @if ($page === 'faq' && $faqs->isNotEmpty())
        <div class="faq mt-4">
            @foreach ($faqs as $faq)
                <details><summary>{{ $faq->question }}</summary><p class="muted">{{ $faq->answer }}</p></details>
            @endforeach
        </div>
    @endif
</div></section>
@endsection

@if ($page === 'faq' && $faqs->isNotEmpty())
    @push('schema')
        <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faqs->map(fn ($faq) => ['@type' => 'Question', 'name' => strip_tags((string) $faq->question), 'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags((string) $faq->answer)]])->values()->all()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    @endpush
@endif
