@extends('frontend.include.app')

@section('title', filled(setting('meta_title')) ? setting('meta_title') : 'Satta King Results & Monthly Charts | ' . setting('title', 'GSK'))
@section('robots_content', request()->hasAny(['year', 'month']) || (int) request()->query('page', 1) > 1 ? 'noindex,follow' : setting('robots_default', 'index,follow'))
@section('meta_description', filled(setting('meta_description')) ? setting('meta_description') : (filled(setting('site_description')) ? setting('site_description') : 'Browse published market results, daily records and monthly charts.'))

@section('content')
@php
    $todayLabel = now()->timezone(config('app.timezone'))->format('d M Y');
    $publishedCount = collect($frontendResults)->filter(fn ($result) => ($result['today'] ?? '--') !== '--')->count();
    $managedSections = $homepageSections ?? collect();
@endphp

<section class="gsk-home-hero">
    <div class="container py-5 py-lg-6">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="gsk-kicker">📅 {{ $todayLabel }} <span class="mx-2">•</span> LIVE MARKET DESK</span>
                <h1 class="display-4 fw-black mt-3 mb-3">
                    {{ setting('homepage_heading_line1', 'Every market.') }}
                    <span class="d-block text-warning">{{ setting('homepage_heading_line2', 'Every record.') }}</span>
                    <span class="d-block">{{ setting('homepage_heading_line3', 'One clear board.') }}</span>
                </h1>
                <p class="gsk-hero-intro mb-4">{{ setting('homepage_intro', 'Find published results, compare recent records and browse market-wise monthly charts in one place.') }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#today-results" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold">🏆 Today's results</a>
                    <a href="#market-charts" class="btn btn-outline-light btn-lg rounded-pill px-4">📊 Browse charts</a>
                    @if (filled(setting('app_download_url')))
                        <a href="{{ setting('app_download_url') }}" target="_blank" rel="noopener noreferrer" class="btn btn-light btn-lg rounded-pill px-4">📲 Download app</a>
                    @endif
                </div>
                <div class="row g-2 mt-4 gsk-hero-stats">
                    <div class="col-4"><div class="gsk-stat"><strong>{{ count($frontendResults) }}</strong><span>Markets</span></div></div>
                    <div class="col-4"><div class="gsk-stat"><strong>{{ count($recordYears) }}</strong><span>Record years</span></div></div>
                    <div class="col-4"><div class="gsk-stat"><strong>{{ $publishedCount }}</strong><span>Published today <span class="visually-hidden">Published result records</span></span></div></div>
                </div>
            </div>
            <div class="col-lg-5">
                <article class="gsk-featured-result">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <span class="gsk-featured-label">⭐ FEATURED MARKET</span>
                        <span class="gsk-featured-date">🗓️ {{ $todayLabel }}</span>
                    </div>
                    @if ($featuredGame)
                        <h2 class="mt-4 mb-1">{{ $featuredGame['name'] }}</h2>
                        <p class="gsk-featured-time">🕒 Result time · {{ $featuredGame['time'] }}</p>
                        @if (($featuredGame['today'] ?? '--') === '--')
                            <div class="gsk-featured-number is-pending">--</div>
                            <span class="gsk-result-state">⌛ Awaiting publication</span>
                        @else
                            <div class="gsk-featured-number">{{ $featuredGame['today'] }}</div>
                            <span class="gsk-result-state is-published">● Published result</span>
                        @endif
                        <div class="gsk-featured-foot">
                            <span>Previous: <strong>{{ $featuredGame['yesterday'] }}</strong></span>
                            <a href="{{ route('frontend.market', ['slug' => $featuredGame['slug']]) }}">Market history →</a>
                        </div>
                    @else
                        <div class="gsk-featured-empty"><span>🎯</span><strong>No featured market</strong><p>Results will appear when markets are configured.</p></div>
                    @endif
                </article>
            </div>
        </div>
    </div>
</section>

@if ($managedSections->where('location', 'first_place')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'first_place')])
@endif

<section class="container gsk-shortcuts py-4" aria-label="Quick links">
    <div class="row g-3">
        <div class="col-6 col-lg-3"><a class="gsk-shortcut" href="#today-results"><span>🏆</span><b>Today's results</b><small>Published & pending</small></a></div>
        <div class="col-6 col-lg-3"><a class="gsk-shortcut" href="#markets"><span>🎮</span><b>Market directory</b><small>Find a market</small></a></div>
        <div class="col-6 col-lg-3"><a class="gsk-shortcut" href="#market-charts"><span>📊</span><b>Monthly charts</b><small>Date-wise records</small></a></div>
        <div class="col-6 col-lg-3"><a class="gsk-shortcut" href="#faq"><span>💡</span><b>Help center</b><small>Common questions</small></a></div>
    </div>
</section>

<section class="gsk-content-section" id="today-results">
    <div class="container py-5">
        <div class="gsk-section-heading"><div><span>THE DAILY BOARD</span><h2>🏆 Today's market results</h2><p>Quickly check today's published result for each market.</p></div><span class="gsk-section-date">{{ $todayLabel }}</span></div>
        <div class="row g-3">
            @forelse ($remainingFrontendResults as $game)
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="gsk-result-tile h-100">
                        <div class="d-flex justify-content-between gap-2"><h3>{{ $game['name'] }}</h3><span class="gsk-market-time">🕒 {{ $game['time'] }}</span></div>
                        <div class="gsk-tile-result {{ ($game['today'] ?? '--') === '--' ? 'is-pending' : '' }}">
                            <small>Today's result</small><strong>{{ $game['today'] }}</strong>
                            @if (($game['today'] ?? '--') !== '--')<span>● PUBLISHED</span>@else<span>⌛ PENDING</span>@endif
                        </div>
                        <a href="{{ route('frontend.market', ['slug' => $game['slug']]) }}">View full market history <b>→</b></a>
                    </article>
                </div>
            @empty
                <div class="col-12"><div class="gsk-empty-state"><span>🎲</span><strong>No active markets are configured.</strong><p>Published records will appear here when a market is activated.</p></div></div>
            @endforelse
        </div>
    </div>
</section>

@if ($managedSections->where('location', 'second_place')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'second_place')])
@endif
@if ($managedSections->where('location', 'second_place_another')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'second_place_another')])
@endif

<section class="gsk-content-section gsk-section-white" id="markets">
    <div class="container py-5">
        <div class="gsk-section-heading"><div><span>MARKET DIRECTORY</span><h2>🎮 Explore all markets</h2><p>Choose a market to open its saved results and historical chart.</p></div><span class="gsk-section-date">{{ count($marketResults) }} markets</span></div>
        <div class="row align-items-center g-2 mb-3">
            <div class="col-12 col-md-7"><label class="input-group gsk-market-search"><span class="input-group-text">🔎</span><input id="market-search" class="form-control" type="search" placeholder="Search by market name…" aria-label="Search markets"></label></div>
            <div class="col-md-5 text-md-end"><small class="text-secondary">Select a market to view its full result history.</small></div>
        </div>
        <div class="row g-3" id="market-grid">
            @forelse ($marketResults as $index => $game)
                <div class="col-12 col-md-6 col-xl-4 gsk-market-column">
                    @include('frontend.partial.market-card', ['game' => $game, 'index' => $index])
                </div>
            @empty
                <div class="col-12"><div class="gsk-empty-state"><span>🗂️</span><strong>No markets to display</strong><p>Activate a market in the admin area to show it here.</p></div></div>
            @endforelse
        </div>
        <p class="gsk-empty-state mt-3" id="no-markets" hidden>No market matches that search.</p>
    </div>
</section>

@if ($managedSections->where('location', 'third_place')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'third_place')])
@endif

<section class="gsk-editorial-band">
    <div class="container py-4"><div class="row align-items-center g-3"><div class="col-lg-3"><span>📚 RECORD DESK</span><h2>Daily & historical records</h2></div><div class="col-lg-9"><p class="mb-0">Browse today's and previous results, compare market-wise numbers and review the saved monthly history. Published results and pending records are clearly identified.</p></div></div></div>
</section>

@if ($managedSections->where('location', 'third_place_another')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'third_place_another')])
@endif

<section class="gsk-content-section" id="market-charts">
    <div class="container py-5">
        <div class="gsk-section-heading"><div><span>MONTHLY ARCHIVE · {{ strtoupper($monthLabel) }}</span><h2>📊 Monthly result charts</h2><p>Date-wise saved records for {{ $monthLabel }}. A dash indicates that no result is recorded for that date.</p></div></div>
        @forelse ($monthlyTables as $tableIndex => $table)
            <div class="gsk-table-card mb-4">
                <div class="gsk-table-heading"><strong>🗓️ {{ $monthLabel }} · Result table {{ $tableIndex + 1 }}</strong><span>{{ count($table['games']) }} markets</span></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0 gsk-month-table">
                        <thead><tr><th scope="col">Date</th>
                            @foreach ($table['games'] as $game)
                                <th scope="col"><a href="{{ route('frontend.market', ['slug' => $game->slug]) }}">{{ $game->name }}</a></th>
                            @endforeach
                        </tr></thead>
                        <tbody>
                            @foreach ($table['dates'] as $date)
                                <tr><th scope="row">{{ $date->format('d') }}</th>
                                    @foreach ($table['games'] as $game)
                                        <td>{{ $table['results'][$game->id][$date->day] ?? '—' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="gsk-empty-state"><span>📊</span><strong>No monthly results available</strong><p>Market records will appear here once they are published.</p></div>
        @endforelse
    </div>
</section>

<section class="gsk-content-section gsk-section-white" id="records">
    <div class="container py-5">
        <div class="gsk-section-heading"><div><span>HISTORICAL REGISTER</span><h2>📚 Explore market archives</h2><p>Open a market to browse its monthly and historical results.</p></div></div>
        <div class="row g-2">
            @forelse ($games as $game)
                <div class="col-6 col-md-4 col-lg-3"><a class="gsk-archive-link" href="{{ route('frontend.market', ['slug' => $game->slug]) }}"><span>📈</span><strong>{{ $game->name }}</strong><span>Open archive →</span></a></div>
            @empty
                <div class="col-12"><div class="gsk-empty-state">No historical markets are available.</div></div>
            @endforelse
        </div>
    </div>
</section>

@if ($managedSections->where('location', 'before_footer')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'before_footer')])
@endif

<section class="gsk-content-section" id="faq">
    <div class="container py-5">
        <div class="gsk-section-heading"><div><span>HELP CENTER</span><h2>💡 Frequently asked questions</h2><p>Information about market records and the result data shown on this website.</p></div></div>
        <div class="accordion gsk-faq" id="gskFaq">
            @forelse ($faqs as $index => $faq)
                <div class="accordion-item">
                    <h3 class="accordion-header"><button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#gskFaq{{ $index }}" aria-expanded="{{ $index ? 'false' : 'true' }}" aria-controls="gskFaq{{ $index }}">{{ $faq->question }}</button></h3>
                    <div id="gskFaq{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}" data-bs-parent="#gskFaq"><div class="accordion-body">{{ $faq->answer }}</div></div>
                </div>
            @empty
                <div class="gsk-empty-state">No frequently asked questions have been added yet.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="gsk-disclaimer" id="disclaimer">
    <div class="container py-4"><div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div><span>⚖️ PLEASE NOTE</span><h2>Records are information, not promises.</h2><p>{{ Str::limit(setting('disclaimer_content'), 180, '…') }}</p></div>
        <a class="btn btn-outline-light rounded-pill px-4" href="{{ route('page', ['page' => 'disclaimer']) }}">Read full disclaimer →</a>
    </div></div>
</section>
@endsection

@section('script')
<script>
(() => {
  const search = document.getElementById('market-search');
  const cards = [...document.querySelectorAll('#market-grid .gsk-market-column')];
  const empty = document.getElementById('no-markets');
  if (!search || !cards.length) return;
  const filter = () => {
    const query = search.value.trim().toLocaleLowerCase();
    let visible = 0;
    cards.forEach(card => {
      const matches = !query || card.textContent.toLocaleLowerCase().includes(query);
      card.hidden = !matches;
      if (matches) visible++;
    });
    if (empty) empty.hidden = visible !== 0;
  };
  search.addEventListener('input', filter, { passive: true });
})();
</script>
@endsection

@section('structured_data')
<script type="application/ld+json">@json($homeSchema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>
@endsection
