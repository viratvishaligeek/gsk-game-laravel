@extends('frontend.include.layout')

@section('meta_title', $game->name . ' Result & Monthly Chart | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_description', 'Browse stored result records and monthly chart for ' . $game->name . '.')
@section('meta_robots', request()->query->count() ? 'noindex,follow' : 'index,follow')

@section('content')
<section class="section">
    <div class="">
        <div class="center mb-4">
            <h1>{{ $game->name }} Result & Monthly Chart</h1>
            <p class="muted">Historical records shown here come from the site's published results.</p>
        </div>
        <form class="card mb-4" method="GET" action="{{ route('frontend.market', ['slug' => $game->slug]) }}">
            <div class="card-body"><div class="row g-3 align-items-end">
                <div class="col-6 col-md-4"><label class="form-label" for="year">Year</label><select class="form-select" id="year" name="year">@foreach ($availableYears as $availableYear)<option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>@endforeach</select></div>
                <div class="col-6 col-md-4"><label class="form-label" for="month">Month</label><select class="form-select" id="month" name="month">@foreach (range(1,12) as $monthNumber)@if ($year < $currentYear || $monthNumber <= $currentMonth)<option value="{{ $monthNumber }}" @selected($month === $monthNumber)>{{ \Carbon\Carbon::create()->month($monthNumber)->format('F') }}</option>@endif @endforeach</select></div>
                <div class="col-12 col-md-4 d-grid"><button class="btn btn-primary" type="submit">Show month</button></div>
            </div></div>
        </form>
        <h2 class="h4 mb-3">{{ $monthLabel }}</h2>
        <div class="table-scroll"><table class="chart-table">
            <thead><tr><th class="wk-h">Date</th><th>Result</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($results as $row)
                    <tr><td class="wk">{{ $row['date']->format('d M Y') }}</td><td>{{ $row['number'] ?? '—' }}</td><td>{{ $row['status'] === 'published' ? 'Published' : ($row['status'] === 'upcoming' ? 'Upcoming' : 'Not published') }}</td></tr>
                @empty
                    <tr><td colspan="3">No dates are available for this month.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <p class="muted small mt-3">A dash means no result is published for that date.</p>
        <p class="mt-4"><a class="btn" href="{{ route('charts.index', ['game_id' => $game->id, 'year' => $year, 'month' => $month]) }}">Open interactive charts</a></p>
        @if ($faqs->isNotEmpty())
            <section class="faq mt-5"><h2 class="h4">{{ $game->name }} FAQs</h2>
                @foreach ($faqs as $faq)<details><summary>{{ $faq->question }}</summary><p class="muted">{{ $faq->answer }}</p></details>@endforeach
            </section>
        @endif
    </div>
</section>
@endsection

@push('schema')
    @php
        $homeCanonical = rtrim((string) app(\App\Services\AppSettingsService::class)->value('canonical_url', config('app.url', url('/'))), '/') . '/';
        $marketCanonical = rtrim($homeCanonical, '/') . '/result/' . $game->slug;
        $marketSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                ['@type' => 'WebPage', 'name' => $game->name . ' Result & Monthly Chart', 'url' => $marketCanonical, 'description' => 'Historical result records and monthly chart for ' . $game->name . '.'],
                ['@type' => 'BreadcrumbList', 'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $homeCanonical],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $game->name, 'item' => $marketCanonical],
                ]],
            ],
        ];
        if ($faqs->isNotEmpty()) {
            $marketSchema['@graph'][] = [
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => strip_tags((string) $faq->question),
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags((string) $faq->answer)],
                ])->values()->all(),
            ];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($marketSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endpush
