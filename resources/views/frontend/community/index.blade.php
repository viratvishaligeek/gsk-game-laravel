@extends('frontend.include.layout')

@section('meta_title', 'Community Discussions | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_description', 'Browse approved community discussions, filter by game and date, and join the conversation.')

@section('content')
<section class="section">
    <div class="">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div><h1 class="h2 mb-1">Community Discussions</h1><p class="text-body-secondary mb-0">Approved posts only. Community content is user-submitted and does not predict future results.</p></div>
            <div class="d-flex flex-wrap gap-2">
                @auth('web')
                    <a class="btn btn-primary" href="{{ route('community.create') }}">Create a post</a>
                    <form action="{{ route('community.logout') }}" method="POST">@csrf<button class="btn btn-outline-secondary" type="submit">Log out</button></form>
                @else
                    <a class="btn btn-primary" href="{{ route('community.login') }}">Sign in</a>
                    <a class="btn btn-outline-primary" href="{{ route('community.register') }}">Create account</a>
                @endauth
            </div>
        </div>

        <div class="row g-3 mb-4" aria-label="Community statistics">
            <div class="col-12 col-sm-4"><div class="card h-100"><div class="card-body"><div class="text-body-secondary small">Approved posts</div><div class="display-6" data-stat="posts">{{ number_format($approvedCount) }}</div></div></div></div>
            <div class="col-12 col-sm-4"><div class="card h-100"><div class="card-body"><div class="text-body-secondary small">Replies on approved discussions</div><div class="display-6" data-stat="replies">{{ number_format($replyCount) }}</div></div></div></div>
            <div class="col-12 col-sm-4"><div class="card h-100"><div class="card-body"><div class="text-body-secondary small">Games discussed</div><div class="display-6" data-stat="games">{{ number_format($gameCount) }}</div></div></div></div>
        </div>

        <div data-community-summary aria-live="polite">@include('frontend.community.partials.game-summary', ['gameSummaries' => $gameSummaries, 'maxPosts' => $maxPosts])</div>

        <form class="card mb-4" method="GET" action="{{ route('community.index') }}" data-community-filters>
            <div class="card-body"><div class="row g-3 align-items-end">
                <div class="col-12 col-md-4"><label class="form-label" for="game_id">Game</label><select class="form-select" name="game_id" id="game_id"><option value="">All games</option>@foreach ($games as $game)<option value="{{ $game->id }}" @selected((string)($filters['game_id'] ?? '') === (string)$game->id)>{{ $game->name }}</option>@endforeach</select></div>
                <div class="col-6 col-md-3"><label class="form-label" for="year">Year</label><select class="form-select" name="year" id="year"><option value="">All years</option>@foreach ($years as $year)<option value="{{ $year }}" @selected((string)($filters['year'] ?? '') === (string)$year)>{{ $year }}</option>@endforeach</select></div>
                <div class="col-6 col-md-3"><label class="form-label" for="month">Month</label><select class="form-select" name="month" id="month"><option value="">All months</option>@foreach (range(1,12) as $month)<option value="{{ $month }}" @selected((string)($filters['month'] ?? '') === (string)$month)>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>@endforeach</select></div>
                <div class="col-12 col-md-2 d-grid"><button class="btn btn-primary" type="submit">Apply filters</button></div>
            </div></div>
        </form>
        <div data-community-posts aria-live="polite">
            @include('frontend.community.partials.posts', ['posts' => $posts])
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
(() => {
    const form = document.querySelector('[data-community-filters]');
    const container = document.querySelector('[data-community-posts]');
    const summary = document.querySelector('[data-community-summary]');
    if (!form || !container || !summary || !window.fetch) return;
    let controller;
    const load = async (url, push = true) => {
        if (controller) controller.abort();
        controller = new AbortController();
        container.setAttribute('aria-busy', 'true');
        const previous = container.innerHTML;
        container.innerHTML = '<p class="text-body-secondary" role="status">Loading community posts…</p>';
        try {
            const response = await fetch(url, {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}, signal: controller.signal});
            if (!response.ok) throw new Error('Unable to load community posts.');
            const data = await response.json();
            container.innerHTML = data.html;
            summary.innerHTML = data.summary_html || '';
            Object.entries(data.stats || {}).forEach(([key, value]) => {
                const node = document.querySelector('[data-stat="' + key + '"]');
                if (node) node.textContent = Number(value).toLocaleString();
            });
            if (push) history.pushState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') container.innerHTML = '<p class="alert alert-warning" role="alert">Posts could not be loaded. Please try again.</p>' + previous;
        } finally {
            container.removeAttribute('aria-busy');
        }
    };
    form.addEventListener('submit', event => {
        event.preventDefault();
        const url = new URL(form.action, window.location.origin);
        new FormData(form).forEach((value, key) => { if (value) url.searchParams.set(key, value); });
        load(url.toString());
    });
    container.addEventListener('click', event => {
        const link = event.target.closest('.pagination a');
        if (!link) return;
        event.preventDefault();
        load(link.href);
    });
    window.addEventListener('popstate', () => {
        const params = new URLSearchParams(window.location.search);
        ['game_id', 'year', 'month'].forEach(key => {
            const field = form.elements.namedItem(key);
            if (field) field.value = params.get(key) || '';
        });
        load(window.location.href, false);
    });
})();
</script>
@endsection
