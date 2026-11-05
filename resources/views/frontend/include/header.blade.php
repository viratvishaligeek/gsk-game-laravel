<header class="v5-header">
  <div class="v5-header-top"><span><strong>●</strong> PUBLIC RESULT ARCHIVE</span><span>{{ now()->timezone(config('app.timezone'))->format('D · d M Y') }}</span></div>
  <nav class="v5-nav" aria-label="Primary navigation">
    <a class="v5-brand" href="{{ route('index') }}" aria-label="{{ setting('title') }} home">
      @if (filled(setting('site_logo')) && is_file(public_path('logos/' . basename(setting('site_logo')))))
        <img src="{{ asset('logos/' . basename(setting('site_logo'))) }}" width="48" height="48" alt="{{ setting('title') }}">
      @else
        <span class="v5-brand-mark" aria-hidden="true">G</span>
      @endif
      <span class="v5-brand-copy"><strong>{{ setting('title') }}</strong><small>{{ setting('site_tagline') }}</small></span>
    </a>
    <ul class="v5-nav-links" id="gskPrimaryNav">
      <li><a href="{{ route('index') }}">Home</a></li>
      <li><a href="{{ route('index') }}#today-results">Live results</a></li>
      <li><a href="{{ route('index') }}#markets">Markets</a></li>
      <li><a href="{{ route('charts.index') }}">Charts</a></li>
      <li><a href="{{ route('community.index') }}">Community</a></li>
      <li><a href="{{ route('information') }}">FAQ</a></li>
      @foreach ($navPages ?? collect() as $navPage)
        <li><a href="{{ route('page', ['page' => $navPage->slug]) }}">{{ $navPage->name }}</a></li>
      @endforeach
      @if (filled(setting('app_download_url')))
        <li><a class="v5-nav-cta" href="{{ setting('app_download_url') }}" target="_blank" rel="noopener noreferrer">Get the app ↗</a></li>
      @endif
    </ul>
    <button class="v5-nav-toggle" type="button" data-v5-menu aria-controls="gskPrimaryNav" aria-expanded="false" aria-label="Open navigation">☰</button>
  </nav>
</header>
<div class="v5-ticker" role="status"><b>ON THE BOARD</b><div class="v5-ticker-track"><span>{{ filled(setting('web_marquee')) ? setting('web_marquee') : 'Today’s published results · Market schedules · Previous records · Monthly charts' }}　✦　{{ filled(setting('web_marquee')) ? setting('web_marquee') : 'Today’s published results · Market schedules · Previous records · Monthly charts' }}</span></div><a href="{{ route('charts.index') }}">Archive ↗</a></div>
<script>
(() => {
  const button = document.querySelector('[data-v5-menu]');
  const menu = document.getElementById('gskPrimaryNav');
  if (!button || !menu) return;
  button.addEventListener('click', () => {
    const open = button.getAttribute('aria-expanded') !== 'true';
    button.setAttribute('aria-expanded', open ? 'true' : 'false');
    button.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    menu.classList.toggle('is-open', open);
  });
})();
</script>
