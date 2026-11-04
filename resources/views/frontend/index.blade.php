@extends('frontend.include.layout')

@php
    $settings = app(\App\Services\AppSettingsService::class);
    $siteName = trim((string) $settings->value('title', config('app.name', 'Gali Satta Kings')));
    $homeTitle = trim(
        (string) $settings->value(
            'homepage_meta_title',
            $settings->value('meta_title', $siteName . ' | Results and Monthly Charts'),
        ),
    );
    $homeDescription = trim(
        (string) $settings->value(
            'homepage_meta_description',
            $settings->value('meta_description', 'Browse published result records and historical monthly charts.'),
        ),
    );
    $heroLine1 = trim((string) $settings->value('homepage_heading_line1', '')) ?: $siteName . ' –';
    $heroLine2 = trim((string) $settings->value('homepage_heading_line2', '')) ?: 'Historical Records';
    $heroLine3 = trim((string) $settings->value('homepage_heading_line3', '')) ?: '& Monthly Charts';
    $heroIntroduction =
        trim((string) $settings->value('homepage_intro', '')) ?:
        'Welcome to ' .
            $siteName .
            ', your online source for market-wise result information and historical records. Explore previous results, monthly charts and year-wise records, with published updates available in one convenient place.';
@endphp

@section('meta_title', $homeTitle)
@section('meta_description', $homeDescription)

@section('content')
    <section class="home-intro py-2 py-lg-2 bg-light text-dark" aria-labelledby="home-intro-title">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-7">
                    <span class="d-inline-flex align-items-center gap-2 text-uppercase fw-semibold small mb-3"><i
                            class="bi bi-bar-chart-line-fill" aria-hidden="true"></i> Market history and published
                        records</span>
                    <h1 id="home-intro-title" class="display-4 fw-bold lh-sm mb-3">
                        {{ $heroLine1 }}
                        <span class="d-block">{{ $heroLine2 }}</span>
                        <span class="d-block">{{ $heroLine3 }}</span>
                    </h1>
                    <p class="lead mb-4">{{ $heroIntroduction }}</p>
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                        @if (!empty($appDownloadUrl))
                            <a class="btn btn-primary btn-lg d-inline-flex align-items-center justify-content-center gap-2"
                                href="{{ $appDownloadUrl }}" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-download" aria-hidden="true"></i><span>Download App</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="home-intro-panel p-2 p-lg-2 rounded-4 bg-white border shadow-sm">
                        <div class="d-flex align-items-center gap-3 mb-2"><span
                                class="home-intro-icon d-inline-flex align-items-center justify-content-center rounded-3"><i
                                    class="bi bi-calendar3 fs-4" aria-hidden="true"></i></span>
                            <div>
                                <div class="fw-bold">Historical records</div>
                                <div class="small text-secondary">Browse results by date</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-2"><span
                                class="home-intro-icon d-inline-flex align-items-center justify-content-center rounded-3"><i
                                    class="bi bi-table fs-4" aria-hidden="true"></i></span>
                            <div>
                                <div class="fw-bold">Monthly charts</div>
                                <div class="small text-secondary">View recorded values and missing dates</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3"><span
                                class="home-intro-icon d-inline-flex align-items-center justify-content-center rounded-3"><i
                                    class="bi bi-check2-circle fs-4" aria-hidden="true"></i></span>
                            <div>
                                <div class="fw-bold">Published updates</div>
                                <div class="small text-secondary">Information from available site records</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="live-hero">
        @if (!empty($featuredGame))
            <div class="live-box">
                <div class="gname">{{ $featuredGame['name'] }}</div>
                <div class="gnum">
                    {{ ($featuredGame['latest_result'] ?? '--') !== '--' ? $featuredGame['latest_result'] : '—' }}</div>

                <a class="btn btn-sm btn-outline-light mt-3"
                    href="{{ route('frontend.market', ['slug' => $featuredGame['slug']]) }}"
                    aria-label="View {{ $featuredGame['name'] }} result history">View market history <i
                        class="bi bi-arrow-right" aria-hidden="true"></i></a>
                <div class="row mt-2">
                    <div class="col-6">
                        <div class="sub">{ कल }</div>
                        <div class="gname">
                            {{ ($featuredGame['yesterday'] ?? '--') !== '--' ? $featuredGame['yesterday'] : '—' }}</div>

                    </div>
                    <div class="col-6">
                        <div class="sub">{ आज }</div>
                        <div class="gname">{{ ($featuredGame['today'] ?? '--') !== '--' ? $featuredGame['today'] : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="live-box">
                <div class="gname">Results</div>
                <div class="gnum">—</div>
                <div class="sub">No published result data is available.</div>
            </div>
        @endif
        <div><span class="badge-live">RESULTS</span> <span class="muted">Database-backed updates</span></div>
        <div class="share-box">
            <div class="share-label">Share {{ $siteName }}</div>
            <div class="share-row">
                <a class="share-wa" href="https://wa.me/?text={{ rawurlencode($siteName . ' — ' . url('/')) }}"
                    target="_blank" rel="noopener noreferrer">WhatsApp</a>
                <a class="share-fb" href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode(url('/')) }}"
                    target="_blank" rel="noopener noreferrer">Facebook</a>
                <a class="share-x"
                    href="https://x.com/intent/tweet?text={{ rawurlencode($siteName . ' results') }}&url={{ rawurlencode(url('/')) }}"
                    target="_blank" rel="noopener noreferrer">X</a>
                <button type="button" class="share-copy" data-copy="{{ url('/') }}">Copy Link</button>
            </div>
        </div>
    </section>

    @include('frontend.include.homepage-sections', [
        'sections' => $homepageSections->get('first_place', collect()),
    ])

    <section class="hero">
        <div class="hero-glows"><span class="g1"></span><span class="g2"></span><span class="g3"></span></div>
        <div class="hero-inner">
            <div class="hero-date" data-live-date>{{ now()->timezone(config('app.timezone'))->format('d F Y | h:i A') }}
            </div>
            @if (!empty($featuredGame))
                <div class="hero-pill-">
                    <div class="hero-pill" data-hero-pill>
                        <span class="dot"></span>
                        <span
                            class="kind">{{ ($featuredGame['latest_result'] ?? '--') !== '--' ? 'Latest published result:' : 'No published result:' }}</span>
                        <span class="name">{{ $featuredGame['name'] }}</span>
                        <span class="extra">—
                            {{ !empty($featuredGame['latest_result_date']) ? \Carbon\Carbon::parse($featuredGame['latest_result_date'])->format('d M Y') : $featuredGame['time'] ?? 'Time not set' }}</span>
                    </div>
                </div>
            @endif
            <div class="hero-cards">
                @forelse ($frontendResults as $gameResult)
                    <a class="hero-card {{ ['db', 'sg', 'fb', 'gb', 'gl', 'ds'][$loop->index % 6] }} {{ ($gameResult['today'] ?? '--') !== '--' ? 'has' : 'wait' }}"
                        href="{{ route('frontend.market', ['slug' => $gameResult['slug']]) }}"
                        data-slug="{{ $gameResult['slug'] }}">
                        <div class="sheen"></div>
                        <div class="badge">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($gameResult['name'], 0, 2)) }}
                        </div>
                        <div class="gname">{{ $gameResult['name'] }}</div>
                        <div class="num">{{ ($gameResult['today'] ?? '--') !== '--' ? $gameResult['today'] : '--' }}
                        </div>
                        <div class="time">{{ $gameResult['time'] ?? '—' }}</div>
                    </a>
                @empty
                    <p class="muted">No active games are available right now.</p>
                @endforelse
            </div>
            <div class="hero-note">Published results are shown from the site's records.</div>
        </div>
    </section>

    @include('frontend.include.homepage-sections', [
        'sections' => $homepageSections->get('second_place', collect()),
    ])

    <section class="lrb-section">
        <div class="lrb-inner">
            <div class="lrb-heading">
                <h2 class="lrb-title">Aaj Or Kal ka Results</h2>
                <p class="">{{ now()->timezone(config('app.timezone'))->format('l, d M Y') }}</p>
            </div>
            <div class="lrb-grid">
                @forelse ($frontendResults as $gameResult)
                    @php $hasResult = ($gameResult['today'] ?? '--') !== '--'; @endphp
                    <a class="lrb-card" data-state="{{ $hasResult ? 'declared' : 'awaited' }}"
                        data-slug="{{ $gameResult['slug'] }}"
                        href="{{ route('frontend.market', ['slug' => $gameResult['slug']]) }}">
                        <div class="lrb-header">
                            <span class="lrb-name">{{ $gameResult['name'] }}</span>
                            <span
                                class="lrb-badge {{ $hasResult ? 'lrb-badge--declared' : 'lrb-badge--awaited' }}">{{ $hasResult ? 'Published' : 'Awaiting result' }}</span>
                        </div>
                        <div class="lrb-body">
                            <div class="container row text-center mt-2">
                                <div class="col-6">
                                    <div class="">{ कल }</div>
                                    <p class="fs-1">
                                        {{ ($gameResult['yesterday'] ?? '--') !== '--' ? $gameResult['yesterday'] : '—' }}
                                    </p>

                                </div>
                                <div class="col-6">
                                    <div class="">{ आज }</div>
                                    <p class="fs-1">
                                        {{ ($gameResult['today'] ?? '--') !== '--' ? $gameResult['today'] : '—' }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </a>
                @empty
                    <p class="muted">No active games are available right now.</p>
                @endforelse
            </div>
        </div>
    </section>

    @include('frontend.include.homepage-sections', [
        'sections' => $homepageSections->get('second_place_another', collect()),
    ])

    <section class="section alt bg-light">
        <div class=" p-0">
            <div class="center">
                <h2 class="text-dark">{{ $monthLabel }} — Monthly Results Chart</h2>
                <p class="muted text-dark">Published values are loaded from stored game results. Missing or future dates
                    are not fabricated.</p>
            </div>
            @forelse ($monthlyTables as $table)
                <div class="table-scroll" style="margin-top:16px">
                    <table class="chart-table table">
                        <thead>
                            <tr>
                                <th class="wk-h bg-dark">Date</th>
                                @foreach ($table['games'] as $game)
                                    <th><a
                                            href="{{ route('frontend.market', ['slug' => $game->slug]) }}">{{ $game->name }}</a>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($table['dates'] as $date)
                                <tr>
                                    <td class="wk bg-dark">{{ $date->format('d') }}</td>
                                    @foreach ($table['games'] as $game)
                                        @php $value = $table['results'][$game->id][$date->day] ?? null; @endphp
                                        <td class="text-dark">
                                            {{ $date->gt(now()->timezone(config('app.timezone'))->startOfDay()) ? '—' : ($value !== null && $value !== '' ? $value : '—') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p class="muted center">No active games are configured, so a monthly chart cannot be displayed yet.</p>
            @endforelse
            <div class="center" style="margin-top:16px">
                @if ($games->isNotEmpty())
                    <a class="btn" href="{{ route('frontend.market', ['slug' => $games->first()->slug]) }}">VIEW GAME
                        CHARTS →</a>
                @endif
            </div>
        </div>
    </section>

    @include('frontend.include.homepage-sections', [
        'sections' => $homepageSections->get('third_place', collect()),
    ])

    <section class="section">
        <div class="">
            <div class="center">
                <h2>Available Games</h2>
                <p class="muted">Choose a game to view its published monthly records and result history.</p>
            </div>
            <div class="seo-games" style="margin-top:16px">
                @forelse ($games as $game)
                    <a class="seo-game" href="{{ route('frontend.market', ['slug' => $game->slug]) }}">
                        <div class="mark">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($game->name, 0, 2)) }}</div>
                        <div>
                            <div class="t">{{ $game->name }}</div>
                            <div class="muted small">
                                {{ $game->result_time ? \Carbon\Carbon::parse($game->result_time)->format('h:i A') : 'Result time not configured' }}
                                · View monthly chart and history</div>
                        </div>
                    </a>
                @empty
                    <p class="muted">No active games have been published yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    @include('frontend.include.homepage-sections', [
        'sections' => $homepageSections->get('third_place_another', collect()),
    ])

    <section class="section alt bg-light">
        <div class="" style="max-width:860px">
            <div class="blog-card" style="margin-bottom:32px">
                <h2>{{ $siteName }}</h2>
                <p class="">{{ $homeDescription }}</p>
                <div class="stat-row">
                    <div>
                        <div class="v">{{ $games->count() }}</div>
                        <div class="muted small">Active games</div>
                    </div>
                    <div>
                        <div class="v">{{ number_format($publishedResultCount) }}</div>
                        <div class="muted small">Published result records</div>
                    </div>
                    <div>
                        <div class="v">
                            {{ $oldestResultDate ? \Carbon\Carbon::parse($oldestResultDate)->format('Y') : '—' }}</div>
                        <div class="muted small">Oldest available record</div>
                    </div>
                </div>
            </div>

            @if ($faqs->isNotEmpty())
                <h2 class="text-dark">Frequently Asked Questions</h2>
                <p class=" text-dark">Answers published by the site administrator.</p>
                <div class="faq" style="margin-bottom:36px">
                    @foreach ($faqs as $faq)
                        <details>
                            <summary>{{ $faq->question }}</summary>
                            <p class="muted">{{ $faq->answer }}</p>
                        </details>
                    @endforeach
                </div>
            @endif

            <h2 class="text-dark">How to view historical results</h2>
            <div class="how-step">
                <div class="num text-dark">01</div>
                <div>
                    <h3 class="text-dark fw-bold">Choose a game</h3>
                    <p class="text-dark">Select an active game from the list above.</p>
                </div>
            </div>
            <div class="how-step">
                <div class="num text-dark">02</div>
                <div>
                    <h3 class="text-dark fw-bold">Select a month and year</h3>
                    <p class="text-dark">Use the available filters on the game's result page to browse stored records.</p>
                </div>
            </div>
            <div class="how-step">
                <div class="num text-dark">03</div>
                <div>
                    <h3 class="text-dark fw-bold">Check published values</h3>
                    <p class="text-dark">Blank values indicate that no result is published for that date.</p>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.include.homepage-sections', [
        'sections' => $homepageSections->get('before_footer', collect()),
    ])
@endsection

@push('schema')
    <script type="application/ld+json">{!! json_encode($homeSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endpush
