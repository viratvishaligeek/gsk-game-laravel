<header class="site-header">
    <div class="ticker">
        <div class="wrap ticker-inner">
            <div class="ticker-track"><div class="ticker-move" data-ticker>
                @if ($frontendResults->isNotEmpty())
                    @foreach ([1, 2] as $tickerCopy)
                        @foreach ($frontendResults as $gameResult)
                            <span class="ticker-item" data-slug="{{ $gameResult['slug'] }}">
                                <span class="ticker-name">{{ \Illuminate\Support\Str::upper($gameResult['name']) }}</span>
                                <span class="muted">→</span>
                                @if (($gameResult['today'] ?? '--') !== '--')
                                    <span class="ticker-num">{{ $gameResult['today'] }}</span>
                                @else
                                    <span class="ticker-wait">{{ $gameResult['time'] ?? '—' }}</span>
                                @endif
                                <span class="muted">|</span>
                            </span>
                        @endforeach
                    @endforeach
                @else
                    <span class="ticker-item">No active games configured</span>
                @endif
            </div></div>
            <span class="ticker-date" data-ticker-date>{{ now()->timezone(config('app.timezone'))->format('l, d M Y') }}</span>
        </div>
    </div>
    <div class="wrap header-row">
        <a href="{{ route('index') }}" class="logo" aria-label="{{ $siteName }} Home">
            <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="logo-img">  {{$siteName}}
        </a>
        <nav class="nav-desktop" aria-label="Main navigation">
            <a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'active' : '' }}">Home</a>
            <a href="{{ route('charts.index') }}" class="{{ request()->routeIs('charts.*') ? 'active' : '' }}">Charts</a>
            <a href="{{ route('information', ['page' => 'about']) }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('community.index') }}" class="{{ request()->routeIs('community.*') ? 'active' : '' }}">Community</a>
            @if ($frontendResults->isNotEmpty())
                <div class="drop">
                    <button type="button" class="drop-btn">Games ▾</button>
                    <div class="drop-menu">
                        @foreach ($frontendResults as $gameResult)
                            <a href="{{ route('frontend.market', ['slug' => $gameResult['slug']]) }}"><span>{{ $gameResult['name'] }}</span><span class="muted">{{ $gameResult['time'] }}</span></a>
                        @endforeach
                    </div>
                </div>
            @endif
            @foreach ($navPages as $navPage)
                <a href="{{ url('/pages/' . $navPage->slug) }}">{{ $navPage->name }}</a>
            @endforeach
        </nav>
    </div>
    <nav class="nav-mobile" aria-label="Mobile navigation">
        <a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'active' : '' }}">Home</a>
        <a href="{{ route('charts.index') }}" class="{{ request()->routeIs('charts.*') ? 'active' : '' }}">Charts</a>
        <a href="{{ route('community.index') }}" class="{{ request()->routeIs('community.*') ? 'active' : '' }}">Community</a>
        @if ($frontendResults->isNotEmpty())
            <a href="{{ route('frontend.market', ['slug' => $frontendResults->first()['slug']]) }}">Results</a>
        @endif
        <a href="{{ route('information', ['page' => 'about']) }}">About</a>
    </nav>
</header>
