<article class="gsk-market-card">
  <div class="gsk-market-card-top"><h3>{{ $game['name'] }}</h3><span class="gsk-market-card-time">{{ $game['time'] }}</span></div>
  <div class="gsk-market-results">
    <div class="gsk-market-value"><small>PREVIOUS</small><strong>{{ $game['yesterday'] }}</strong></div>
    <div class="gsk-market-value today"><small>TODAY</small><strong>{{ $game['today'] }}</strong></div>
  </div>
  @if (($game['today'] ?? '--') !== '--')
    <span class="badge rounded-pill text-bg-success align-self-center">Published</span>
  @else
    <span class="badge rounded-pill text-bg-light text-secondary align-self-center">Awaiting</span>
  @endif
  <a class="gsk-market-card-link" href="{{ route('frontend.market', ['slug' => $game['slug']]) }}"><span>Open this market’s archive</span><span aria-hidden="true">↗</span></a>
</article>
