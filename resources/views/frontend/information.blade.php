@extends('frontend.include.app')
@section('title', $title . ' | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($copy), 155))
@section('robots_content', 'index,follow')
@section('content')
<section class="gsk-v3-inner-hero"><div class="container py-4 py-lg-5">
  <span class="gsk-v3-kicker"><span class="gsk-v3-live-dot"></span> INFORMATION DESK</span>
  <h1 class="mt-3">{{ $title }}</h1><p>Helpful information and guidance from {{ setting('title') }}.</p>
  <div class="gsk-v3-inner-links"><a href="{{ route('index') }}#markets">🎮 Browse markets</a><a href="{{ route('charts.index') }}">📊 Monthly charts</a><a href="{{ route('community.index') }}">👥 Community</a></div>
</div></section>
<section class="container py-4 py-lg-5 gsk-v3-document-layout">
  <article class="gsk-v3-document-card">
    <div class="gsk-v3-document-kicker">📘 INFORMATION CENTER <span>·</span> {{ $title }}</div>
    <div class="gsk-information-copy">{{ $copy }}</div>
    @if ($page === 'faq' && $faqs->isNotEmpty())
      <div class="accordion gsk-v3-faq mt-4" id="informationFaq">
        @foreach ($faqs as $index => $faq)
          <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#informationFaq{{ $index }}">{{ $faq->question }}</button></h2>
            <div id="informationFaq{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}" data-bs-parent="#informationFaq"><div class="accordion-body">{{ $faq->answer }}</div></div>
          </div>
        @endforeach
      </div>
    @endif
    <a class="btn btn-outline-primary rounded-pill mt-4" href="{{ route('index') }}">← Back to home</a>
  </article>
</section>
@endsection
@if ($page === 'faq' && $faqs->isNotEmpty())
@push('schema')
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>$faqs->map(fn($faq)=>['@type'=>'Question','name'=>strip_tags((string)$faq->question),'acceptedAnswer'=>['@type'=>'Answer','text'=>strip_tags((string)$faq->answer)]])->values()->all()], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endpush
@endif
