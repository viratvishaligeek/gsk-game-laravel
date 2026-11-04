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

<section class="gsk-v3-hero">
    <div class="container py-5 py-xl-6">
        <div class="row align-items-center g-4 g-xl-5">
            <div class="col-lg-7">
                <div class="gsk-v3-kicker"><span class="gsk-v3-live-dot"></span> LIVE RESULT DESK <i></i> {{ $todayLabel }}</div>
                <h1>{{ setting('homepage_heading_line1', 'Today’s results.') }} <span>{{ setting('homepage_heading_line2', 'Every market.') }}</span></h1>
                <p class="gsk-v3-hero-copy">{{ setting('homepage_intro', 'A clear daily board for published results, market timings and date-wise historical charts.') }}</p>
                <div class="gsk-v3-hero-actions">
                    <a href="#today-results" class="btn btn-warning btn-lg">🏆 Check today’s board <b>→</b></a>
                    <a href="#monthly-records" class="btn btn-outline-light btn-lg">📊 Explore charts</a>
                    @if (filled(setting('app_download_url')))
                        <a href="{{ setting('app_download_url') }}" target="_blank" rel="noopener noreferrer" class="btn btn-light btn-lg">📲 Get the app</a>
                    @endif
                </div>
                <div class="gsk-v3-metrics" aria-label="Market statistics">
                    <div><strong>{{ count($frontendResults) }}</strong><span>Active markets</span></div>
                    <div><strong>{{ $publishedCount }}</strong><span>Results published</span></div>
                    <div><strong>{{ count($recordYears) }}</strong><span>Archive years</span></div>
                </div>
            </div>
            <div class="col-lg-5">
                <article class="gsk-v3-spotlight">
                    <div class="gsk-v3-spotlight-top"><span>⭐ MOST RECENT UPDATE</span><small>🗓️ {{ $todayLabel }}</small></div>
                    @if ($featuredGame)
                        <div class="gsk-v3-spotlight-market">{{ $featuredGame['name'] }}</div>
                        <div class="gsk-v3-spotlight-time">🕒 Scheduled result · {{ $featuredGame['time'] }}</div>
                        <div class="gsk-v3-spotlight-number {{ ($featuredGame['today'] ?? '--') === '--' ? 'is-pending' : '' }}">{{ $featuredGame['today'] ?? '--' }}</div>
                        <div class="gsk-v3-spotlight-state {{ ($featuredGame['today'] ?? '--') === '--' ? 'is-waiting' : 'is-published' }}">
                            {{ ($featuredGame['today'] ?? '--') === '--' ? '⌛ Awaiting publication' : '● Result published' }}
                        </div>
                        <div class="gsk-v3-spotlight-bottom">
                            <span>Previous <strong>{{ $featuredGame['yesterday'] ?? '--' }}</strong></span>
                            <a href="{{ route('frontend.market', ['slug' => $featuredGame['slug']]) }}">Open history →</a>
                        </div>
                    @else
                        <div class="gsk-v3-spotlight-empty"><span>🎯</span><strong>No featured market yet</strong><p>Configured markets will appear here.</p></div>
                    @endif
                </article>
            </div>
        </div>
    </div>
</section>

@if ($managedSections->where('location', 'first_place')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'first_place')])
@endif

<section class="gsk-v3-taskbar container" aria-label="Quick destinations">
    <a href="#today-results"><span>🏆</span><b>Today’s board</b><small>Published & pending</small></a>
    <a href="#markets"><span>🎮</span><b>Market directory</b><small>Find a market</small></a>
    <a href="#monthly-records"><span>📊</span><b>Monthly charts</b><small>Date-wise records</small></a>
    <a href="{{ route('community.index') }}"><span>👥</span><b>Community</b><small>Join discussions</small></a>
</section>

<section class="gsk-v3-section gsk-v3-results" id="today-results">
    <div class="container py-5">
        <div class="gsk-v3-section-head">
            <div><span>THE DAILY BOARD</span><h2>🏆 Today’s market results</h2><p>Latest published numbers with each market’s scheduled time.</p></div>
            <div class="gsk-v3-date-chip">🗓️ {{ $todayLabel }}</div>
        </div>
        <div class="row g-3">
            @forelse ($remainingFrontendResults as $game)
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="gsk-v3-result-card h-100">
                        <div class="gsk-v3-result-card-head"><span>🎯 {{ $game['name'] }}</span><small>🕒 {{ $game['time'] }}</small></div>
                        <div class="gsk-v3-result-number {{ ($game['today'] ?? '--') === '--' ? 'is-pending' : '' }}">
                            <div><small>TODAY’S NUMBER</small><strong>{{ $game['today'] ?? '--' }}</strong></div>
                            <span>{{ ($game['today'] ?? '--') === '--' ? '⌛ Waiting' : '● Published' }}</span>
                        </div>
                        <div class="gsk-v3-result-card-foot"><span>Yesterday <b>{{ $game['yesterday'] ?? '--' }}</b></span><a href="{{ route('frontend.market', ['slug' => $game['slug']]) }}">Market archive →</a></div>
                    </article>
                </div>
            @empty
                <div class="col-12"><div class="gsk-v3-empty"><span>🎲</span><strong>No active markets are configured.</strong><p>Published results appear here when markets are activated.</p></div></div>
            @endforelse
        </div>
    </div>
</section>

@if ($managedSections->where('location', 'second_place')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'second_place')])
@endif

<section class="gsk-v3-section gsk-v3-market-section" id="markets">
    <div class="container py-5">
        <div class="gsk-v3-section-head">
            <div><span>FIND YOUR MARKET</span><h2>🎮 Explore the market directory</h2><p>Search by name or filter by today’s publication status.</p></div>
            <div class="gsk-v3-date-chip">{{ count($marketResults) }} more markets</div>
        </div>
        <div class="gsk-v3-market-tools">
            <label class="gsk-v3-search"><span>🔎</span><input id="market-search" type="search" placeholder="Search market name…" aria-label="Search markets"></label>
            <div class="gsk-v3-filter-tabs" role="group" aria-label="Filter markets">
                <button type="button" class="is-active" data-market-filter="all" aria-pressed="true">All <span>{{ count($marketResults) }}</span></button>
                <button type="button" data-market-filter="published" aria-pressed="false">🟢 Published <span>{{ collect($marketResults)->where('today', '!=', '--')->count() }}</span></button>
                <button type="button" data-market-filter="pending" aria-pressed="false">⌛ Awaiting <span>{{ collect($marketResults)->where('today', '--')->count() }}</span></button>
            </div>
        </div>
        <div class="gsk-v3-market-rail" id="market-grid">
            @forelse ($marketResults as $index => $game)
                <div class="gsk-v3-market-item" data-result-state="@if (($game['today'] ?? '--') === '--') pending @else published @endif">
                    @include('frontend.partial.market-card', ['game' => $game, 'index' => $index])
                </div>
            @empty
                <div class="w-100"><div class="gsk-v3-empty"><span>🗂️</span><strong>No markets to display</strong><p>Activate a market to show it here.</p></div></div>
            @endforelse
        </div>
        <p class="gsk-v3-no-results" id="no-markets" hidden>No market matches that search.</p>
    </div>
</section>

@if ($managedSections->where('location', 'second_place_another')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'second_place_another')])
@endif

<section class="gsk-v3-archive-band" id="monthly-records">
    <div class="container py-5">
        <div class="gsk-v3-section-head gsk-v3-section-head-light">
            <div><span>THE RECORD ROOM · {{ strtoupper($monthLabel) }}</span><h2>📊 Monthly result charts</h2><p>Browse the saved daily record for each market in the selected month.</p></div>
            <a href="{{ route('charts.index') }}" class="btn btn-warning">Open full chart archive →</a>
        </div>
        @forelse ($monthlyTables as $tableIndex => $table)
            <div class="gsk-v3-chart-card mb-4">
                <div class="gsk-v3-chart-card-head"><strong>🗓️ {{ $monthLabel }} · Record set {{ $tableIndex + 1 }}</strong><span>{{ count($table['games']) }} markets</span></div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0 gsk-v3-month-table">
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
            <div class="gsk-v3-empty"><span>📊</span><strong>No monthly records available</strong><p>Market results will appear here when published.</p></div>
        @endforelse
    </div>
</section>

@if ($managedSections->where('location', 'third_place')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'third_place')])
@endif

<section class="gsk-v3-section">
    <div class="container py-5">
        <div class="gsk-v3-section-head"><div><span>HISTORICAL INDEX</span><h2>📚 Browse every market archive</h2><p>Open a market to explore its saved monthly and historical records.</p></div></div>
        <div class="gsk-v3-archive-grid">
            @forelse ($games as $game)
                <a href="{{ route('frontend.market', ['slug' => $game->slug]) }}"><span>📈</span><strong>{{ $game->name }}</strong><small>Open archive ↗</small></a>
            @empty
                <div class="gsk-v3-empty">No historical markets are available.</div>
            @endforelse
        </div>
    </div>
</section>

@if ($managedSections->where('location', 'third_place_another')->isNotEmpty())
    @include('frontend.partial.homepage-sections', ['sections' => $managedSections->where('location', 'third_place_another')])
@endif

<section class="gsk-v3-section gsk-v3-faq-section" id="faq">
    <div class="container py-5">
        <div class="gsk-v3-section-head"><div><span>HELP & INFORMATION</span><h2>💡 Frequently asked questions</h2><p>Helpful notes about the market records and information shown here.</p></div></div>
        <div class="accordion gsk-v3-faq" id="gskFaq">
            @forelse ($faqs as $index => $faq)
                <div class="accordion-item">
                    <h3 class="accordion-header"><button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#gskFaq{{ $index }}" aria-expanded="{{ $index ? 'false' : 'true' }}" aria-controls="gskFaq{{ $index }}">{{ $faq->question }}</button></h3>
                    <div id="gskFaq{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}" data-bs-parent="#gskFaq"><div class="accordion-body">{{ $faq->answer }}</div></div>
                </div>
            @empty
                <div class="gsk-v3-empty">No frequently asked questions have been added yet.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="gsk-v3-disclaimer">
    <div class="container py-4"><div><span>⚖️ INFORMATION NOTICE</span><h2>Records are information, not promises.</h2><p>{{ Str::limit(setting('disclaimer_content'), 200, '…') }}</p></div><a href="{{ route('page', ['page' => 'disclaimer']) }}">Read full disclaimer →</a></div>
</section>

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
