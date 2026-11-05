@forelse ($sections as $homepageSection)
  <section class="v5-managed" id="homepage-section-{{ $homepageSection->id }}" data-homepage-section="{{ $homepageSection->id }}">
    <div class="v5-managed-inner">
      <div class="v5-managed-label"><span>✦</span><br>FEATURED<br>INFORMATION</div>
      <div class="v5-managed-content">
        @if (filled($homepageSection->title))<h2>{{ $homepageSection->title }}</h2>@endif
        @if (filled($homepageSection->content))
          <article @if (filled($homepageSection->background)) style="background: {{ $homepageSection->background }}" @endif>{!! $homepageSection->content !!}</article>
        @endif
      </div>
    </div>
  </section>
@empty
@endforelse
