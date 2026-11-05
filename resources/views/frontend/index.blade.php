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
  <div class="v5-home-hero v5-home-grid">
    <div class="v5-home-copy">
      <span class="v5-kicker">LIVE RESULT DESK · {{ $todayLabel }}</span>
      <h1>{{ setting('homepage_heading_line1', 'Today’s results.') }} <span>{{ setting('homepage_heading_line2', 'Every market.') }}</span></h1>
      <p>{{ setting('homepage_intro', 'A clear daily board for published results, market timings and date-wise historical charts.') }}</p>
      <div class="v5-home-actions">
        <a class="v5-button v5-button-gold" href="#today-results">Explore today’s board <b>↘</b></a>
        <a class="v5-button v5-button-glass" href="#monthly-records">Open the record room</a>
        @if (filled(setting('app_download_url')))
          <a class="v5-button v5-button-glass" href="{{ setting('app_download_url') }}" target="_blank" rel="noopener noreferrer">Get the app ↗</a>
        @endif
      </div>
      <div class="v5-home-facts" aria-label="Market statistics">
        <div><strong>{{ count($frontendResults) }}</strong><small>Active markets</small></div>
        <div><strong>{{ $publishedCount }}</strong><small>Published result records</small></div>
        <div><strong>{{ now()->timezone(config('app.timezone'))->year }}</strong><small>Record year</small></div>
      </div>
    </div>
    <div class="v5-feature-stage">
      <span class="v5-feature-orbit" aria-hidden="true"></span>
      <article class="gsk-featured-result" aria-label="Most recently updated market">
        <div class="v5-feature-label"><span>✦ LATEST UPDATE</span><small>{{ $todayLabel }}</small></div>
        @if ($featuredGame)
          <div class="v5-feature-name">{{ $featuredGame['name'] }}</div>
          <div class="v5-feature-time">Scheduled result · {{ $featuredGame['time'] }}</div>
          <div class="v5-feature-number {{ ($featuredGame['today'] ?? '--') === '--' ? 'is-pending' : '' }}">{{ $featuredGame['today'] ?? '--' }}</div>
          <div class="v5-feature-state {{ ($featuredGame['today'] ?? '--') === '--' ? 'is-waiting' : '' }}">
            <span aria-hidden="true">{{ ($featuredGame['today'] ?? '--') === '--' ? '◷' : '●' }}</span>
            {{ ($featuredGame['today'] ?? '--') === '--' ? 'Awaiting publication' : 'Result published' }}
          </div>
          <div class="v5-feature-foot"><span>Previous <strong>{{ $featuredGame['yesterday'] ?? '--' }}</strong></span><a href="{{ route('frontend.market', ['slug' => $featuredGame['slug']]) }}">View history ↗</a></div>
        @else
          <div class="v5-feature-empty"><span>◎</span><strong>No featured market yet</strong><p>Configured markets will appear here.</p></div>
        @endif
      </article>
    </div>
  </div>
</section>

@if ($managedSections->where('location', 'first_place')->isNotEmpty())
  @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'first_place')])
@endif

<nav class="v5-quick-rail" aria-label="Quick destinations">
  <a href="#today-results"><span class="v5-quick-icon">↗</span><span><b>Today’s board</b><small>Published and pending</small></span></a>
  <a href="#markets"><span class="v5-quick-icon">⌕</span><span><b>Market directory</b><small>Find a market</small></span></a>
  <a href="#monthly-records"><span class="v5-quick-icon">▦</span><span><b>Monthly charts</b><small>Date-wise records</small></span></a>
  <a href="{{ route('community.index') }}"><span class="v5-quick-icon">◎</span><span><b>Community</b><small>Join discussions</small></span></a>
</nav>

<section class="v5-section v5-results-section" id="today-results">
  <div class="v5-section-inner">
    <header class="v5-section-head">
      <div><span class="v5-kicker">THE DAILY REGISTER</span><h2>Results, market by market.</h2><p>Published numbers are shown alongside each market’s scheduled result time.</p></div>
      <time class="v5-date-stamp" datetime="{{ now()->timezone(config('app.timezone'))->toDateString() }}">{{ $todayLabel }}</time>
    </header>
    <div class="v5-results-layout">
      <aside class="v5-results-index"><strong>{{ str_pad((string) count($remainingFrontendResults), 2, '0', STR_PAD_LEFT) }}</strong><small>MARKETS ON TODAY’S BOARD</small><p class="mt-3 text-secondary small">A dash indicates that today’s result has not been published yet.</p></aside>
      <div class="v5-result-list">
        @forelse ($remainingFrontendResults as $game)
          <article class="v5-result-row">
            <div><div class="v5-result-meta"><i></i><span>{{ ($game['today'] ?? '--') === '--' ? 'AWAITING RESULT' : 'PUBLISHED' }}</span></div><h3>{{ $game['name'] }}</h3><p>Scheduled · {{ $game['time'] }} · Yesterday {{ $game['yesterday'] ?? '--' }}</p></div>
            <div class="v5-result-value {{ ($game['today'] ?? '--') === '--' ? 'is-pending' : '' }}"><strong>{{ $game['today'] ?? '--' }}</strong><small>TODAY’S NUMBER</small></div>
          </article>
        @empty
          <div class="gsk-v3-empty"><strong>No active markets are configured.</strong><p>Published results appear here when markets are activated.</p></div>
        @endforelse
      </div>
    </div>
  </div>
</section>

@if ($managedSections->where('location', 'second_place')->isNotEmpty())
  @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'second_place')])
@endif
@if ($managedSections->where('location', 'second_place_another')->isNotEmpty())
  @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'second_place_another')])
@endif

<section class="v5-section v5-market-section" id="markets">
  <div class="v5-section-inner">
    <header class="v5-section-head"><div><span class="v5-kicker">MARKET EXPLORER</span><h2>Find the market you’re following.</h2><p>Search the live directory or narrow it down by publication status.</p></div><div class="v5-date-stamp">{{ count($marketResults) }} markets</div></header>
    <div class="v5-market-console">
      <aside class="v5-market-aside">
        <span class="v5-kicker">DIRECTORY TOOLS</span><h3>Search the board.</h3><p>Use a market name or choose a result status to focus the list.</p>
        <div class="gsk-v3-market-tools">
          <label class="gsk-v3-search"><span aria-hidden="true">⌕</span><input id="market-search" type="search" placeholder="Search market…" aria-label="Search markets"></label>
          <div class="gsk-v3-filter-tabs" role="group" aria-label="Filter markets">
            <button type="button" class="is-active" data-market-filter="all" aria-pressed="true">All <span>{{ count($marketResults) }}</span></button>
            <button type="button" data-market-filter="published" aria-pressed="false">Published <span>{{ collect($marketResults)->where('today', '!=', '--')->count() }}</span></button>
            <button type="button" data-market-filter="pending" aria-pressed="false">Awaiting <span>{{ collect($marketResults)->where('today', '--')->count() }}</span></button>
          </div>
        </div>
      </aside>
      <div class="v5-market-list" id="market-grid">
        @forelse ($marketResults as $index => $game)
          <div class="gsk-v3-market-item" data-result-state="@if (($game['today'] ?? '--') === '--') pending @else published @endif">
            @include('frontend.partial.market-card', ['game' => $game, 'index' => $index])
          </div>
        @empty
          <div class="gsk-v3-empty"><strong>No markets to display</strong><p>Activate a market to show it here.</p></div>
        @endforelse
      </div>
    </div>
    <p class="gsk-v3-no-results mt-3" id="no-markets" hidden>No market matches that search.</p>
  </div>
</section>

<section class="v5-section v5-chart-section" id="monthly-records"><span id="market-charts" aria-hidden="true"></span>
  <div class="v5-section-inner">
    <header class="v5-section-head"><div><span class="v5-kicker">THE RECORD ROOM · {{ strtoupper($monthLabel) }}</span><h2>A month, laid out day by day.</h2><p>Read the saved daily record across every market in the selected period.</p></div><a class="v5-button v5-button-gold" href="{{ route('charts.index') }}">Full chart archive ↗</a></header>
    @forelse ($monthlyTables as $tableIndex => $table)
      <section class="v5-chart-table-shell mb-4">
        <header class="v5-chart-shell-head"><strong>{{ $monthLabel }} · Record set {{ $tableIndex + 1 }}</strong><span>{{ count($table['games']) }} markets</span></header>
        <div class="table-responsive"><table class="table table-bordered align-middle mb-0 gsk-v3-month-table">
          <thead><tr><th scope="col">Date</th>@foreach ($table['games'] as $game)<th scope="col"><a href="{{ route('frontend.market', ['slug' => $game->slug]) }}">{{ $game->name }}</a></th>@endforeach</tr></thead>
          <tbody>@foreach ($table['dates'] as $date)<tr><th scope="row">{{ $date->format('d') }}</th>@foreach ($table['games'] as $game)<td>{{ $table['results'][$game->id][$date->day] ?? '—' }}</td>@endforeach</tr>@endforeach</tbody>
        </table></div>
      </section>
    @empty
      <div class="gsk-v3-empty"><strong>No monthly records available</strong><p>Market results will appear here when published.</p></div>
    @endforelse
    <div class="v5-chart-footer"><span>Historical records are shown as stored for the selected month.</span><a href="{{ route('charts.index') }}">Compare all markets →</a></div>
  </div>
</section>

@if ($managedSections->where('location', 'third_place')->isNotEmpty())
  @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'third_place')])
@endif

<section class="v5-section v5-archive-index" id="records">
  <div class="v5-section-inner">
    <header class="v5-section-head"><div><span class="v5-kicker">HISTORICAL INDEX</span><h2>Every market has a record.</h2><p>Choose a market to open its month-by-month archive.</p></div></header>
    <div class="v5-archive-index-list">
      @forelse ($games as $game)
        <a href="{{ route('frontend.market', ['slug' => $game->slug]) }}"><span>{{ $game->name }}</span><b aria-hidden="true">↗</b></a>
      @empty
        <div class="gsk-v3-empty">No historical markets are available.</div>
      @endforelse
    </div>
  </div>
</section>

@if ($managedSections->where('location', 'third_place_another')->isNotEmpty())
  @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'third_place_another')])
@endif

<section class="v5-section v5-faq-section" id="faq">
  <div class="v5-section-inner v5-faq-layout">
    <header class="v5-faq-intro"><span class="v5-kicker">HELP DESK</span><h2>Good to know.</h2><p class="text-secondary">Answers to common questions about the records and information shown on this site.</p></header>
    <div class="accordion gsk-v3-faq" id="gskFaq">
      @forelse ($faqs as $index => $faq)
        <div class="accordion-item"><h3 class="accordion-header"><button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#gskFaq{{ $index }}" aria-expanded="{{ $index ? 'false' : 'true' }}" aria-controls="gskFaq{{ $index }}">{{ $faq->question }}</button></h3>
          <div id="gskFaq{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}" data-bs-parent="#gskFaq"><div class="accordion-body">{{ $faq->answer }}</div></div>
        </div>
      @empty
        <div class="gsk-v3-empty">No frequently asked questions have been added yet.</div>
      @endforelse
    </div>
  </div>
</section>

<section class="v5-disclaimer" id="disclaimer"><div class="v5-disclaimer-inner"><div><span class="v5-kicker">INFORMATION NOTICE</span><h2>Records are information, not promises.</h2><p>{{ filled(setting('disclaimer_content')) ? Str::limit(setting('disclaimer_content'), 200, '…') : 'Please review the information and local requirements before using public result records.' }}</p></div><a href="{{ route('page', ['page' => 'disclaimer']) }}">Read the full disclaimer ↗</a></div></section>

@if ($managedSections->where('location', 'before_footer')->isNotEmpty())
  @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'before_footer')])
@endif
@endsection

@section('script')
<script>
(() => {
  const search = document.getElementById('market-search');
  const cards = [...document.querySelectorAll('#market-grid .gsk-v3-market-item')];
  const empty = document.getElementById('no-markets');
  const filters = [...document.querySelectorAll('[data-market-filter]')];
  if (!search || !cards.length) return;
  let activeFilter = 'all';
  const filter = () => {
    const query = search.value.trim().toLocaleLowerCase();
    let visible = 0;
    cards.forEach(card => {
      const matchesSearch = !query || card.textContent.toLocaleLowerCase().includes(query);
      const matchesState = activeFilter === 'all' || card.dataset.resultState === activeFilter;
      const matches = matchesSearch && matchesState;
      card.hidden = !matches;
      if (matches) visible++;
    });
    if (empty) empty.hidden = visible !== 0;
  };
  filters.forEach(button => button.addEventListener('click', () => {
    activeFilter = button.dataset.marketFilter || 'all';
    filters.forEach(item => {
      const selected = item === button;
      item.classList.toggle('is-active', selected);
      item.setAttribute('aria-pressed', selected ? 'true' : 'false');
    });
    filter();
  }));
  search.addEventListener('input', filter, { passive: true });
})();
</script>
@endsection

@section('structured_data')
<script type="application/ld+json">@json($homeSchema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>
@endsection
