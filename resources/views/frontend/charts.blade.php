@extends('frontend.include.layout')

@section('meta_title', 'Monthly Results Charts | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_description', 'Browse monthly result records by game, month, and year.')
@section('meta_robots', request()->query->count() ? 'noindex,follow' : 'index,follow')

@section('content')
<section class="section">
    <div class="">
        <div class="center mb-4">
            <h1>Monthly Results Charts</h1>
            <p class="muted">Select a game, month, and year to view stored result records.</p>
        </div>
        <form class="card mb-4" method="GET" action="{{ route('charts.index') }}" data-chart-filters data-endpoint="{{ route('charts.data') }}" data-current-year="{{ now()->timezone(config('app.timezone'))->year }}" data-current-month="{{ now()->timezone(config('app.timezone'))->month }}">
            <div class="card-body"><div class="row g-3 align-items-end">
                <div class="col-12 col-md-4"><label class="form-label" for="game_id">Game</label><select class="form-select" name="game_id" id="game_id"><option value="">All active games</option>@foreach ($games as $game)<option value="{{ $game->id }}" @selected((string)($selectedGameId ?? '') === (string)$game->id)>{{ $game->name }}</option>@endforeach</select></div>
                <div class="col-6 col-md-3"><label class="form-label" for="year">Year</label><select class="form-select" name="year" id="year">@foreach ($availableYears as $availableYear)<option value="{{ $availableYear }}" @selected($year === $availableYear)>{{ $availableYear }}</option>@endforeach</select></div>
                <div class="col-6 col-md-3"><label class="form-label" for="month">Month</label><select class="form-select" name="month" id="month">@foreach (range(1,12) as $monthNumber)@if ($year < now()->timezone(config('app.timezone'))->year || $monthNumber <= now()->timezone(config('app.timezone'))->month)<option value="{{ $monthNumber }}" @selected($month === $monthNumber)>{{ \Carbon\Carbon::create()->month($monthNumber)->format('F') }}</option>@endif @endforeach</select></div>
                <div class="col-12 col-md-2 d-grid"><button class="btn btn-primary" type="submit">Update chart</button></div>
            </div></div>
        </form>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h2 class="h4 mb-0" data-chart-title>{{ $monthLabel }}</h2>
            <span class="small text-body-secondary" data-chart-status aria-live="polite">{{ number_format($publishedCount) }} published records</span>
        </div>
        <div class="table-scroll" data-chart-wrap>
            <table class="chart-table" data-chart-table>
                <thead><tr><th class="wk-h">Date</th>@foreach ($games as $game)<th>{{ $game->name }}</th>@endforeach</tr></thead>
                <tbody>
                    @forelse ($chartRows as $row)
                        <tr><td class="wk">{{ $row['date']->format('d M') }}</td>@foreach ($games as $game)<td>{{ $row['values'][$game->id] ?? '—' }}</td>@endforeach</tr>
                    @empty
                        <tr><td colspan="{{ $games->count() + 1 }}">No active games are configured.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="muted small mt-3">A dash means no published value is stored for that date. Future dates are not shown as results.</p>
    </div>
</section>
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
