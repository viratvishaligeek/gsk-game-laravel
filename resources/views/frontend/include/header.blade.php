<header class="gsk-v3-site-header">
  <div class="gsk-v3-header-top"><div class="container d-flex align-items-center justify-content-between gap-2">
    <span><i></i> PUBLIC RESULT ARCHIVE</span><span>📅 {{ now()->timezone(config('app.timezone'))->format('D, d M Y') }}</span>
  </div></div>
  <nav class="navbar navbar-expand-lg gsk-v3-navbar">
    <div class="container">
      <a class="navbar-brand gsk-v3-brand" href="{{ route('index') }}" aria-label="{{ setting('title') }} home">
        @if (filled(setting('site_logo')) && is_file(public_path('logos/' . basename(setting('site_logo')))))
          <img src="{{ asset('logos/' . basename(setting('site_logo'))) }}" width="46" height="46" alt="{{ setting('title') }}" class="gsk-brand-logo">
        @else
          <span class="gsk-v3-brand-mark">🎯</span>
        @endif
        <span><strong>{{ setting('title') }}</strong><small>{{ setting('site_tagline') }}</small></span>
      </a>
      <button class="navbar-toggler gsk-v3-nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#gskPrimaryNav"
        aria-controls="gskPrimaryNav" aria-expanded="false" aria-label="Toggle navigation"><span>☰</span></button>
      <div class="collapse navbar-collapse" id="gskPrimaryNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">🏠 Home</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}#today-results">🏆 Live board</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}#markets">🎮 Markets</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('index') . '#monthly-records' }}">📊 Charts</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('community.index') }}">👥 Community</a></li>
          @foreach ($navPages ?? collect() as $navPage)
            <li class="nav-item"><a class="nav-link" href="{{ route('page', ['page' => $navPage->slug]) }}">{{ $navPage->name }}</a></li>
          @endforeach
          @if (filled(setting('app_download_url')))
            <li class="nav-item ms-lg-2"><a class="btn gsk-v3-nav-cta" href="{{ setting('app_download_url') }}" target="_blank" rel="noopener noreferrer">📲 Get the app <span>↗</span></a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>
</header>
<div class="gsk-v3-announcement" role="status"><div class="container d-flex align-items-center gap-2">
  <span>📢</span><div class="gsk-v3-announcement-window"><div class="gsk-v3-announcement-track">
    <span>{{ filled(setting('web_marquee')) ? setting('web_marquee') : 'Today’s published results · Market schedules · Previous records · Monthly charts' }}</span>
    <span aria-hidden="true">{{ filled(setting('web_marquee')) ? setting('web_marquee') : 'Today’s published results · Market schedules · Previous records · Monthly charts' }}</span>
  </div></div>
  <a href="{{ route('index') . '#monthly-records' }}">Explore archive →</a>
</div></div>
