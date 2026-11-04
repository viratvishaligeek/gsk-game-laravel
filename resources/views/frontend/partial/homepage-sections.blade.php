@forelse ($sections as $homepageSection)
  <section class="homepage-managed-section py-4" id="homepage-section-{{ $homepageSection->id }}" data-homepage-section="{{ $homepageSection->id }}">
    <div class="container">
      @if (filled($homepageSection->title))<div class="gsk-section-heading"><div><span>FEATURED INFORMATION</span><h2>{{ $homepageSection->title }}</h2></div></div>@endif
      @if (filled($homepageSection->content))<article class="homepage-managed-content" @if (filled($homepageSection->background)) style="background: {{ $homepageSection->background }}" @endif>{!! $homepageSection->content !!}</article>@endif
    </div>
  </section>
@empty
@endforelse
