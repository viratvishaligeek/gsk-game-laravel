@extends('frontend.include.app')
@section('title', $game->name . ' ' . $monthLabel . ' Satta King Result Chart | ' . setting('title', 'GSK'))
@section('meta_description',
    'Browse saved date-wise results for ' .
    $game->name .
    ' in ' .
    $monthLabel .
    '. Select a
    month and year to review market records.')
@section('meta_keywords', $game->name . ', Satta King result, monthly result chart')
@section('robots_content', request()->query->count() > 0 ? 'noindex,follow' : setting('robots_default', 'index,follow'))
@section('content')
    <header class="v6-market-hero">
        <div class="v6-wrap">
            <nav class="v6-breadcrumb"><a href="{{ route('index') }}">Home</a> / <a
                    href="{{ route('index') }}#markets">Markets</a> / {{ $game->name }}</nav><span
                class="v6-eyebrow eyebrow-purple">MARKET HISTORY</span>
            <h1>{{ $game->name }}</h1>
            <p>Explore saved results for this market, one date at a time. Choose a month and year to view its archive.</p>
            <div class="v6-market-summary"><a href="{{ route('index') }}#markets">← All markets</a><a
                    href="{{ route('charts.index', ['game_id' => $game->id]) }}">Compare charts ↗</a><a
                    href="{{ route('community.index') }}">Community ↗</a></div>
        </div>
    </header>
    <section class="v6-wrap v6-market-workspace">
        <aside class="v6-period-panel"><span class="v6-eyebrow eyebrow-blue">ARCHIVE FILTER</span>
            <h2>Choose a period</h2>
            <p class="small text-secondary">Results refresh when the month or year changes.</p>
            <form method="GET" action="{{ route('frontend.market', ['slug' => $game->slug]) }}" data-result-filter
                data-endpoint="{{ route('frontend.chart') }}"><input type="hidden" data-csrf-token
                    value="{{ csrf_token() }}">
                <div><label class="form-label" for="result-year">Year</label><select class="form-select" id="result-year"
                        name="year" data-result-year>
                        @foreach ($availableYears as $yearOption)
                            <option value="{{ $yearOption }}" {{ (int) $yearOption === (int) $year ? 'selected' : '' }}>
                                {{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="form-label" for="result-month">Month</label><select class="form-select" id="result-month"
                        name="month" data-result-month>
                        @for ($monthOption = 1; $monthOption <= 12; $monthOption++)
                            <option value="{{ $monthOption }}" {{ $monthOption === (int) $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate($year, $monthOption, 1, config('app.timezone'))->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div><button class="v6-btn v6-btn-blue w-100" type="submit">Update results ↗</button>
            </form>
            <div class="mt-4 pt-3 border-top"><small class="text-secondary">SELECTED MARKET</small><strong
                    class="d-block">{{ $game->name }}</strong></div>
        </aside>
        <section class="v6-archive-panel" aria-labelledby="archive-heading">
            <header class="v6-archive-panel-head">
                <div><span class="v6-eyebrow eyebrow-cyan">DATE-WISE RESULTS</span>
                    <h2 id="archive-heading">{{ $game->name }}</h2>
                    <p data-table-month-label>{{ $monthLabel }}</p>
                </div><span class="v6-count-chip" data-month-label>{{ count($results) }} days</span>
            </header>
            <p class="gsk-v3-result-status" data-result-status role="status" aria-live="polite" hidden></p>
            <div class="gsk-v3-archive-empty mb-3" data-result-empty
                {{ $results->contains(fn(array $row) => $row['status'] === 'published') ? 'hidden' : '' }}><strong>No
                    published
                    results for this month.</strong>
                <p>Choose another month or year to review saved records.</p>
            </div>
            <div class="v6-archive-table">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">DAY</th>
                                <th scope="col">{{ $game->name }} · RESULT</th>
                            </tr>
                        </thead>
                        <tbody data-result-body>
                            @foreach ($results as $entry)
                                <tr data-result-row="{{ $entry['date']->day }}">
                                    <th scope="row">{{ $entry['date']->format('d') }}</th>
                                    <td data-result-cell>{{ $entry['number'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <nav class="v6-archive-links"><a href="{{ route('index') }}#markets">← Market directory</a><a
                    href="{{ route('charts.index') }}">All charts →</a></nav>
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
