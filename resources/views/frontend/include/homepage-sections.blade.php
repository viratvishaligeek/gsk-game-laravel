@forelse ($sections as $homepageSection)
    <section class="section homepage-managed-section" data-homepage-section="{{ $homepageSection->id }}">
        <div class="">
            @if (filled($homepageSection->title))
                <h2 class="homepage-managed-title">{{ $homepageSection->title }}</h2>
            @endif
            @if (filled($homepageSection->content))
                <div class="homepage-managed-content">{!! $homepageSection->content !!}</div>
            @endif
        </div>
    </section>
@empty
@endforelse
