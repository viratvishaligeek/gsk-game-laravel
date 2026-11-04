<header class="gsk-site-header">
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('index') }}" aria-label="{{ setting('title') }} home">
        @if (filled(setting('site_logo')) && is_file(public_path('logos/' . basename(setting('site_logo')))))
          <img src="{{ asset('logos/' . basename(setting('site_logo'))) }}" width="42" height="42" alt="{{ setting('title') }}" class="gsk-brand-logo">
        @else
          <span class="gsk-brand-fallback">🎯</span>
        @endif
        <span class="gsk-brand-copy"><strong>{{ setting('title') }}</strong><small>{{ setting('site_tagline') }}</small></span>
      </a>
      <button class="navbar-toggler gsk-nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#gskPrimaryNav"
        aria-controls="gskPrimaryNav" aria-expanded="false" aria-label="Toggle navigation"><span>☰</span></button>
      <div class="collapse navbar-collapse" id="gskPrimaryNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">🏠 Home</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}#today-results">🏆 Results</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}#markets">🎮 Markets</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}#market-charts">📊 Charts</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('community.index') }}">👥 Community</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}#records">📚 Records</a></li>
          @foreach ($navPages ?? collect() as $navPage)
            <li class="nav-item"><a class="nav-link" href="{{ route('page', ['page' => $navPage->slug]) }}">{{ $navPage->name }}</a></li>
          @endforeach
          @if (filled(setting('app_download_url')))
            <li class="nav-item ms-lg-2"><a class="btn btn-primary btn-sm px-3 rounded-pill" href="{{ setting('app_download_url') }}" target="_blank" rel="noopener noreferrer">📲 Get the app</a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>
</header>
<div class="gsk-announcement" role="status"><div class="container d-flex align-items-center gap-2"><span>📢</span>
  @if (filled(setting('web_marquee')))
    <span>{{ setting('web_marquee') }}</span>
  @else
    <span>Today's results · Previous results · Market schedules · Historical charts · Year-wise records</span>
  @endif
</div></div>
