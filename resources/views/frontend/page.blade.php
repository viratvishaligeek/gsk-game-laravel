@extends('frontend.include.layout')

@section('meta_title', trim((string) ($page->meta_title ?? '')) !== '' ? $page->meta_title : $page->name . ' | ' . config('app.name'))
@section('meta_description', trim((string) ($page->meta_description ?? '')) !== '' ? $page->meta_description : \Illuminate\Support\Str::limit(strip_tags((string) $page->content), 155))
@section('meta_keywords', $page->meta_keywords ?? '')
@section('meta_robots', ($page->noindex ?? false) ? 'noindex,follow' : (request()->query->count() ? 'noindex,follow' : 'index,follow'))

@section('content')
<section class="section"><div class="" style="max-width:1000px">
    <article class="blog-card">
        <h1>{{ $page->name }}</h1>
        <div class="cms-page-content">{!! $page->content !!}</div>
    </article>
    @if ($faqs->isNotEmpty())
        <section class="faq mt-4"><h2 class="h4">Frequently Asked Questions</h2>
            @foreach ($faqs as $faq)<details><summary>{{ $faq->question }}</summary><p class="muted">{{ $faq->answer }}</p></details>@endforeach
        </section>
    @endif
</div></section>
@endsection

@push('schema')
    @php
        $homeCanonical = rtrim((string) app(\App\Services\AppSettingsService::class)->value('canonical_url', config('app.url', url('/'))), '/') . '/';
        $pageCanonical = rtrim($homeCanonical, '/') . '/pages/' . $page->slug;
        $pageSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                ['@type' => 'WebPage', 'name' => $page->name, 'url' => $pageCanonical, 'description' => \Illuminate\Support\Str::limit(strip_tags((string) $page->content), 250)],
                ['@type' => 'BreadcrumbList', 'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $homeCanonical],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $page->name, 'item' => $pageCanonical],
                ]],
            ],
        ];
        if ($faqs->isNotEmpty()) {
            $pageSchema['@graph'][] = [
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => strip_tags((string) $faq->question),
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags((string) $faq->answer)],
                ])->values()->all(),
            ];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endpush
