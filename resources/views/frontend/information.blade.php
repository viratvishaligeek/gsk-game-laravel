@extends('frontend.include.app')
@section('title', $title . ' | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($copy), 155))
@section('robots_content', 'index,follow')
@section('content')
<header class="v6-page-hero"><div class="v6-wrap"><span class="v6-eyebrow eyebrow-purple">INFORMATION CENTRE</span><h1>{{ $title }}</h1><p>Helpful information and guidance from {{ setting('title') }}.</p></div></header><section class="v6-wrap v6-page-content"><aside class="v6-page-rail"><b>Explore more</b><a href="{{ route('index') }}#markets">Browse markets ↗</a><a href="{{ route('charts.index') }}">Monthly charts ↗</a><a href="{{ route('community.index') }}">Community ↗</a><a href="{{ route('index') }}">Return home ↗</a></aside><article class="v6-document"><span class="v6-eyebrow eyebrow-blue">REFERENCE / {{ strtoupper($title) }}</span><div class="gsk-information-copy mt-3">{{ $copy }}</div>@if ($page==='faq' && $faqs->isNotEmpty())<section class="mt-5"><h2 class="h4 fw-bold mb-3">Frequently asked questions</h2><div class="accordion v6-faq" id="informationFaq">@foreach ($faqs as $index=>$faq)<div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button {{ $index?'collapsed':'' }}" type="button" data-bs-toggle="collapse" data-bs-target="#informationFaq{{ $index }}" aria-expanded="{{ $index?'false':'true' }}">{{ $faq->question }}</button></h2><div id="informationFaq{{ $index }}" class="accordion-collapse collapse {{ $index?'':'show' }}" data-bs-parent="#informationFaq"><div class="accordion-body">{{ $faq->answer }}</div></div></div>@endforeach</div></section>@endif</article></section>

@if ($page === 'faq' && $faqs->isNotEmpty())
@push('schema')
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>$faqs->map(fn($faq)=>['@type'=>'Question','name'=>strip_tags((string)$faq->question),'acceptedAnswer'=>['@type'=>'Answer','text'=>strip_tags((string)$faq->answer)]])->values()->all()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endpush
@endif
