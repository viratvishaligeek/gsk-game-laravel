@extends('frontend.include.app')
@section('title', $game->name . ' ' . $monthLabel . ' Satta King Result Chart | ' . setting('title', 'GSK'))
@section('meta_description', 'Browse saved date-wise results for ' . $game->name . ' in ' . $monthLabel . '. Select a month and year to review market records.')
@section('meta_keywords', $game->name . ', Satta King result, monthly result chart')
@section('robots_content', request()->query->count() > 0 ? 'noindex,follow' : setting('robots_default', 'index,follow'))
@section('content')
<section class="gsk-v3-archive-hero">
  <div class="container py-4 py-lg-5">
    <nav aria-label="Breadcrumb"><ol class="breadcrumb mb-4"><li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ route('index') }}#markets">Markets</a></li><li class="breadcrumb-item active" aria-current="page">{{ $game->name }}</li></ol></nav>
    <div class="row align-items-end g-4">
      <div class="col-lg-8">
        <span class="gsk-v3-kicker"><span class="gsk-v3-live-dot"></span> MARKET RECORD ROOM</span>
        <h1 class="mt-3">{{ $game->name }}</h1>
        <p>Explore the saved day-by-day results for this market. Choose a month and year to move through the archive.</p>
        <div class="gsk-v3-archive-hero-actions"><a href="{{ route('index') }}#markets">🎮 All markets</a><a href="{{ route('charts.index', ['game_id' => $game->id]) }}">📊 Compare charts</a><a href="{{ route('community.index') }}">👥 Community</a></div>
      </div>
      <div class="col-lg-4"><div class="gsk-v3-archive-summary"><small>SELECTED PERIOD</small><strong data-month-label>{{ $monthLabel }}</strong><span>{{ count($results) }} calendar dates</span></div></div>
    </div>
  </div>
</section>

<section class="container py-4 py-lg-5 gsk-v3-archive-content">
  <div class="gsk-v3-period-panel mb-4">
    <div class="gsk-v3-period-heading"><div><span>🗓️ CHANGE THE PERIOD</span><h2>Browse market history</h2><p>Results refresh automatically when you select a different month or year.</p></div><span>📈 DATE-WISE ARCHIVE</span></div>
    <form method="GET" action="{{ route('frontend.market', ['slug' => $game->slug]) }}" class="row g-3 align-items-end" data-result-filter data-endpoint="{{ route('frontend.chart') }}">
      <input type="hidden" data-csrf-token value="{{ csrf_token() }}">
      <div class="col-12 col-sm-5"><label class="form-label" for="result-year">Year</label><select class="form-select" id="result-year" name="year" data-result-year>@foreach ($availableYears as $yearOption)<option value="{{ $yearOption }}" {{ (int) $yearOption === (int) $year ? 'selected' : '' }}>{{ $yearOption }}</option>@endforeach</select></div>
      <div class="col-12 col-sm-5"><label class="form-label" for="result-month">Month</label><select class="form-select" id="result-month" name="month" data-result-month>@for ($monthOption=1;$monthOption<=12;$monthOption++)<option value="{{ $monthOption }}" {{ $monthOption === (int) $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate($year,$monthOption,1,config('app.timezone'))->translatedFormat('F') }}</option>@endfor</select></div>
      <div class="col-12 col-sm-2"><button class="btn btn-primary w-100" type="submit">Update ↗</button></div>
    </form>
  </div>
  <p class="gsk-v3-result-status" data-result-status role="status" aria-live="polite" hidden></p>
  <div class="gsk-v3-archive-empty mb-3" data-result-empty {{ $results->contains(fn(array $row) => $row['status'] === 'published') ? 'hidden' : '' }}><span>🗓️</span><strong>No published results for this month.</strong><p>Choose another month or year to review saved records.</p></div>
  <div class="gsk-v3-archive-table-card">
    <div class="gsk-v3-archive-table-head"><div><span>📋</span><strong>{{ $game->name }} <i>·</i> <span data-table-month-label>{{ $monthLabel }}</span></strong></div><small>{{ count($results) }} days in this month</small></div>
    <div class="table-responsive"><table class="table align-middle mb-0 gsk-v3-archive-table"><thead><tr><th>Date</th><th>{{ $game->name }} result</th></tr></thead><tbody data-result-body>
      @foreach ($results as $entry)<tr data-result-row="{{ $entry['date']->day }}"><th scope="row">{{ $entry['date']->format('d') }}</th><td data-result-cell>{{ $entry['number'] ?? '—' }}</td></tr>@endforeach
    </tbody></table></div>
  </div>
  <div class="gsk-v3-archive-back"><a href="{{ route('index') }}#markets">← Back to market directory</a><a href="{{ route('charts.index') }}">Open all monthly charts →</a></div>
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
