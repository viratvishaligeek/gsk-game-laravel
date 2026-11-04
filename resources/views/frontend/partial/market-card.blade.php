<article class="gsk-market-card">
  <div class="gsk-market-card-top"><h3>{{ $game['name'] }}</h3><span class="gsk-market-card-time">🕒 {{ $game['time'] }}</span></div>
  <div class="gsk-market-results">
    <div class="gsk-market-value"><small>Yesterday</small><strong>{{ $game['yesterday'] }}</strong></div>
    <div class="gsk-market-value today"><small>Today</small><strong>{{ $game['today'] }}</strong></div>
  </div>
  @if (($game['today'] ?? '--') !== '--')<span class="badge rounded-pill text-bg-success align-self-start">● Published today</span>@else<span class="badge rounded-pill text-bg-light text-secondary align-self-start">⌛ Awaiting result</span>@endif
  <a class="gsk-market-card-link" href="{{ route('frontend.market', ['slug' => $game['slug']]) }}"><span>View monthly result chart</span><span>→</span></a>
</article>
