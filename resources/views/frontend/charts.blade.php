@extends('frontend.include.app')
@section('title', 'Monthly Results Charts | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_description', 'Browse monthly result records by game, month, and year.')
@section('robots_content', request()->query->count() ? 'noindex,follow' : 'index,follow')
@section('content')
<header class="v5-chart-hero"><div class="v5-chart-hero-inner"><div><span class="v5-kicker">HISTORICAL RECORDS / ALL MARKETS</span><h1>Monthly result charts.</h1><p>Move through the archive by market, month and year. Each row represents a calendar date; each column represents a market.</p></div><nav class="d-flex flex-wrap gap-3"><a href="{{ route('index') }}#today-results">Today’s results ↗</a><a href="{{ route('index') }}#markets">Market directory ↗</a><a href="{{ route('community.index') }}">Community ↗</a></nav></div></header>
<main class="v5-chart-workspace">
  <form class="v5-chart-controls" method="GET" action="{{ route('charts.index') }}" data-chart-filters data-endpoint="{{ route('charts.data') }}" data-current-year="{{ now()->timezone(config('app.timezone'))->year }}" data-current-month="{{ now()->timezone(config('app.timezone'))->month }}">
    <div><label for="game_id">MARKET</label><select class="form-select" name="game_id" id="game_id"><option value="">All active markets</option>@foreach ($games as $game)<option value="{{ $game->id }}" @selected((string)($selectedGameId ?? '') === (string)$game->id)>{{ $game->name }}</option>@endforeach</select></div>
    <div><label for="year">YEAR</label><select class="form-select" name="year" id="year">@foreach ($availableYears as $availableYear)<option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>@endforeach</select></div>
    <div><label for="month">MONTH</label><select class="form-select" name="month" id="month">@foreach (range(1,12) as $monthNumber)@if ($year < now()->timezone(config('app.timezone'))->year || $monthNumber <= now()->timezone(config('app.timezone'))->month)<option value="{{ $monthNumber }}" @selected($month === $monthNumber)>{{ \Carbon\Carbon::create()->month($monthNumber)->format('F') }}</option>@endif @endforeach</select></div>
    <button class="v5-button v5-button-gold" type="submit">Refresh chart ↗</button>
  </form>
  <section class="v5-chart-output" aria-labelledby="chart-output-title">
    <header class="v5-chart-output-head"><div><span class="v5-kicker">SELECTED PERIOD</span><h2 id="chart-output-title" data-chart-title>{{ $monthLabel }}</h2></div><div class="text-end"><strong class="fs-2 fw-bold" data-chart-status aria-live="polite">{{ number_format($publishedCount) }}</strong><small class="d-block text-secondary">published records</small></div></header>
    <div class="v5-chart-output-table" data-chart-wrap>
      <div class="gsk-v3-chart-table-card">
        <div class="v5-chart-shell-head"><strong>Daily result matrix</strong><span class="small text-secondary">Scroll horizontally to see all markets</span></div>
        <div class="table-responsive"><table class="table table-hover align-middle mb-0 gsk-v3-chart-table" data-chart-table>
          <thead><tr><th>Date</th>@foreach ($games as $game)<th>{{ $game->name }}</th>@endforeach</tr></thead>
          <tbody>@forelse ($chartRows as $row)<tr><th>{{ $row['date']->format('d M') }}</th>@foreach ($games as $game)<td>{{ $row['values'][$game->id] ?? '—' }}</td>@endforeach</tr>@empty<tr><td colspan="{{ $games->count()+1 }}">No active games are configured.</td></tr>@endforelse</tbody>
        </table></div>
      </div>
    </div>
    <p class="gsk-v3-chart-footnote">A dash means no published value is stored for that date. Future dates are not shown as results.</p>
  </section>
</main>
@endsection

@section('script')
<script>
(() => {
    const form = document.querySelector('[data-chart-filters]');
    const table = document.querySelector('[data-chart-table]');
    const title = document.querySelector('[data-chart-title]');
    const status = document.querySelector('[data-chart-status]');
    if (!form || !table || !window.fetch) return;
    let activeRequest;
    const currentYear = Number(form.dataset.currentYear);
    const currentMonth = Number(form.dataset.currentMonth);
    const monthSelect = form.elements.namedItem('month');
    const yearSelect = form.elements.namedItem('year');
    const limitFutureMonths = () => {
        [...monthSelect.options].forEach(option => {
            option.disabled = Number(yearSelect.value) === currentYear && Number(option.value) > currentMonth;
        });
        if (monthSelect.selectedOptions[0]?.disabled) monthSelect.value = String(currentMonth);
    };
    limitFutureMonths();
    yearSelect.addEventListener('change', limitFutureMonths);
    const load = async (push = true) => {
        const params = new URLSearchParams(new FormData(form));
        for (const [key, value] of [...params.entries()]) if (!value) params.delete(key);
        const url = new URL(form.dataset.endpoint, window.location.origin);
        url.search = params.toString();
        if (activeRequest) activeRequest.abort();
        activeRequest = new AbortController();
        status.textContent = 'Loading chart…';
        status.setAttribute('role', 'status');
        try {
            const response = await fetch(url, {headers: {'Accept': 'application/json'}, signal: activeRequest.signal});
            if (!response.ok) throw new Error('Chart data could not be loaded.');
            const data = await response.json();
            const head = document.createElement('tr');
            const dateHead = document.createElement('th');
            dateHead.className = 'wk-h';
            dateHead.textContent = 'Date';
            head.appendChild(dateHead);
            data.games.forEach(game => {
                const th = document.createElement('th');
                th.textContent = game.name;
                head.appendChild(th);
            });
            table.tHead.replaceChildren(head);
            const body = document.createElement('tbody');
            let published = 0;
            data.rows.forEach(row => {
                const tr = document.createElement('tr');
                const date = document.createElement('td');
                date.className = 'wk';
                date.textContent = row.label;
                tr.appendChild(date);
                row.values.forEach(value => {
                    const td = document.createElement('td');
                    td.textContent = value.number === null ? '—' : String(value.number);
                    td.dataset.status = value.status;
                    if (value.number !== null) published++;
                    tr.appendChild(td);
                });
                body.appendChild(tr);
            });
            if (!data.rows.length) {
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = data.games.length + 1;
                td.textContent = 'No dates are available for this selection.';
                tr.appendChild(td);
                body.appendChild(tr);
            }
            table.tBodies[0]?.remove();
            table.appendChild(body);
            title.textContent = data.month_label;
            status.textContent = published.toLocaleString() + ' published records';
            if (push) {
                const pageUrl = new URL(window.location.href);
                pageUrl.search = params.toString();
                history.pushState({}, '', pageUrl);
            }
        } catch (error) {
            if (error.name !== 'AbortError') status.textContent = 'Chart data could not be loaded. Please retry.';
        }
    };
    form.addEventListener('submit', event => { event.preventDefault(); load(); });
    form.querySelectorAll('select').forEach(select => select.addEventListener('change', () => load()));
    window.addEventListener('popstate', () => {
        const params = new URLSearchParams(window.location.search);
        ['game_id', 'year', 'month'].forEach(key => {
            const field = form.elements.namedItem(key);
            if (field) field.value = params.get(key) || (key === 'year' ? '{{ $year }}' : key === 'month' ? '{{ $month }}' : '');
        });
        load(false);
    });
})();
</script>
@endsection
