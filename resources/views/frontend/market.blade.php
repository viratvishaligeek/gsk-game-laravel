@extends('frontend.include.app')
@section('title', $game->name . ' ' . $monthLabel . ' Satta King Result Chart | ' . setting('title', 'GSK'))
@section('meta_description', 'Browse saved date-wise results for ' . $game->name . ' in ' . $monthLabel . '. Select a month and year to review market records.')
@section('meta_keywords', $game->name . ', Satta King result, monthly result chart')
@section('robots_content', request()->query->count() > 0 ? 'noindex,follow' : setting('robots_default', 'index,follow'))
@section('content')
<header class="v5-market-hero">
  <div class="v5-market-hero-inner">
    <div>
      <nav class="v5-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('index') }}">Home</a> / <a href="{{ route('index') }}#markets">Markets</a> / {{ $game->name }}</nav>
      <span class="v5-kicker mt-4">MARKET ARCHIVE / DATE-WISE RECORD</span>
      <h1>{{ $game->name }}</h1>
      <p>One market, one timeline. Select a period to inspect its saved results day by day, or compare this market against the wider archive.</p>
      <div class="d-flex flex-wrap gap-3 mt-4"><a href="{{ route('index') }}#markets" class="fw-bold text-decoration-none">← All markets</a><a href="{{ route('charts.index', ['game_id' => $game->id]) }}" class="fw-bold text-decoration-none">Compare charts ↗</a><a href="{{ route('community.index') }}" class="fw-bold text-decoration-none">Community ↗</a></div>
    </div>
    <aside class="v5-market-stamp"><small>SELECTED RECORD PERIOD</small><strong data-month-label>{{ $monthLabel }}</strong><span>{{ count($results) }} calendar dates</span></aside>
  </div>
</header>
<section class="v5-market-workspace">
  <aside class="v5-period-control">
    <span class="v5-kicker">ARCHIVE CONTROLS</span><h2>Choose a period</h2><p class="small text-secondary">The record table updates when the month or year changes.</p>
    <form method="GET" action="{{ route('frontend.market', ['slug' => $game->slug]) }}" data-result-filter data-endpoint="{{ route('frontend.chart') }}">
      <input type="hidden" data-csrf-token value="{{ csrf_token() }}">
      <div><label class="form-label" for="result-year">Year</label><select class="form-select" id="result-year" name="year" data-result-year>@foreach ($availableYears as $yearOption)<option value="{{ $yearOption }}" {{ (int) $yearOption === (int) $year ? 'selected' : '' }}>{{ $yearOption }}</option>@endforeach</select></div>
      <div><label class="form-label" for="result-month">Month</label><select class="form-select" id="result-month" name="month" data-result-month>@for ($monthOption=1;$monthOption<=12;$monthOption++)<option value="{{ $monthOption }}" {{ $monthOption === (int) $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate($year,$monthOption,1,config('app.timezone'))->translatedFormat('F') }}</option>@endfor</select></div>
      <button class="v5-button v5-button-gold w-100" type="submit">Update archive ↗</button>
    </form>
    <div class="mt-4 pt-3 border-top"><small class="text-secondary">MARKET</small><strong class="d-block">{{ $game->name }}</strong><small class="text-secondary">Results are displayed as stored.</small></div>
  </aside>
  <section class="v5-archive-main" aria-labelledby="archive-heading">
    <header class="v5-archive-main-head"><div><span class="v5-kicker">DAILY REGISTER</span><h2 id="archive-heading">{{ $game->name }} results</h2><p class="small text-secondary mb-0" data-table-month-label>{{ $monthLabel }}</p></div><span class="v5-date-stamp">{{ count($results) }} days</span></header>
    <p class="gsk-v3-result-status" data-result-status role="status" aria-live="polite" hidden></p>
    <div class="gsk-v3-archive-empty mb-3" data-result-empty {{ $results->contains(fn(array $row) => $row['status'] === 'published') ? 'hidden' : '' }}><strong>No published results for this month.</strong><p>Choose another month or year to review saved records.</p></div>
    <div class="v5-archive-table">
      <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th scope="col">DAY</th><th scope="col">{{ $game->name }} · RESULT</th></tr></thead><tbody data-result-body>
        @foreach ($results as $entry)<tr data-result-row="{{ $entry['date']->day }}"><th scope="row">{{ $entry['date']->format('d') }}</th><td data-result-cell>{{ $entry['number'] ?? '—' }}</td></tr>@endforeach
      </tbody></table></div>
    </div>
    <nav class="v5-archive-back" aria-label="Archive navigation"><a href="{{ route('index') }}#markets">← Market directory</a><a href="{{ route('charts.index') }}">All market charts →</a></nav>
  </section>
</section>
@endsection

@section('script')
    <script>
        (function() {
            const form = document.querySelector('[data-result-filter]');
            if (!form) return;
            const yearSelect = form.querySelector('[data-result-year]');
            const monthSelect = form.querySelector('[data-result-month]');
            const csrf = form.querySelector('[data-csrf-token]').value;
            const status = document.querySelector('[data-result-status]');
            const empty = document.querySelector('[data-result-empty]');
            const tbody = document.querySelector('[data-result-body]');
            const monthLabel = document.querySelector('[data-month-label]');
            let activeController = null;
            let requestNumber = 0;

            function setStatus(message, isError) {
                status.hidden = !message;
                status.textContent = message || '';
                status.classList.toggle('is-error', Boolean(isError));
            }

            async function loadResults() {
                const thisRequest = ++requestNumber;
                if (activeController) activeController.abort();
                activeController = new AbortController();
                setStatus('Loading results…', false);
                form.setAttribute('aria-busy', 'true');

                try {
                    const response = await fetch(form.dataset.endpoint, {
                        method: 'POST',
                        credentials: 'same-origin',
                        signal: activeController.signal,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            slug: @json($game->slug),
                            year: yearSelect.value,
                            month: monthSelect.value
                        })
                    });
                    let payload = {};
                    try {
                        payload = await response.json();

                    } catch (parseError) {
                        payload = {};
                    }
                    if (thisRequest !== requestNumber) return;
                    if (!response.ok) throw new Error(payload.message ||
                        'Unable to load results. Please choose a valid month and year.');

                    tbody.replaceChildren();
                    let hasPublished = false;
                    payload.days.forEach(function(day) {
                        const row = document.createElement('tr');
                        row.dataset.resultRow = String(day.day);
                        const dateCell = document.createElement('th');
                        dateCell.scope = 'row';
                        dateCell.style = 'text-align:center';
                        dateCell.textContent = String(day.day).padStart(2, '0');
                        const resultCell = document.createElement('td');
                        resultCell.dataset.resultCell = '';
                        resultCell.style = 'font-weight:bold';

                        resultCell.textContent = day.result === null ? '—' : String(day.result);
                        if (day.result !== null) hasPublished = true;
                        row.append(dateCell, resultCell);
                        tbody.appendChild(row);
                    });
                    if (payload.month_label) {
                        if (monthLabel) monthLabel.textContent = payload.month_label;
                        const tableMonthLabel = document.querySelector('[data-table-month-label]');
                        if (tableMonthLabel) tableMonthLabel.textContent = payload.month_label;
                    }
                    empty.hidden = hasPublished;
                    setStatus(hasPublished ? 'Results updated.' : 'No published results for this month.', false);
                } catch (error) {
                    if (error.name === 'AbortError' || thisRequest !== requestNumber) return;
                    setStatus(error.message || 'A network error occurred. Please try again.', true);
                } finally {
                    if (thisRequest === requestNumber) form.removeAttribute('aria-busy');
                }
            }

            yearSelect.addEventListener('change', function() {
                const currentYear = @json($currentYear);
                const currentMonth = @json($currentMonth);
                if (Number(yearSelect.value) === currentYear && Number(monthSelect.value) > currentMonth) {
                    monthSelect.value = String(currentMonth);
                }
                loadResults();
            });
            monthSelect.addEventListener('change', loadResults);
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                loadResults();
            });
        })();
    </script>
@endsection
