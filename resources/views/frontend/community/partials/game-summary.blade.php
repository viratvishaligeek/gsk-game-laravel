<section class="v5-game-summary" aria-labelledby="community-game-summary-title">
  <header><span class="v5-kicker">COMMUNITY PULSE</span><h2 id="community-game-summary-title">Approved posts by game</h2><p>This summary follows the selected game and date filters.</p></header>
  <div class="v5-game-summary-bars">
    @forelse ($gameSummaries as $summary)
      <div class="v5-game-summary-row">
        <div><strong>{{ $summary['name'] }}</strong><b>{{ number_format($summary['posts_count']) }}</b></div>
        <div class="v5-game-summary-track" role="progressbar" aria-label="{{ $summary['name'] }} approved posts" aria-valuenow="{{ $summary['posts_count'] }}" aria-valuemin="0" aria-valuemax="{{ $maxPosts }}"><span style="width: {{ min(100, (int) round(($summary['posts_count'] / max(1, (int) $maxPosts)) * 100)) }}%"></span></div>
      </div>
    @empty
      <p class="text-secondary">No approved posts match the selected filters.</p>
    @endforelse
  </div>
  <details class="v5-game-summary-details"><summary>View totals as a table</summary>
    <div class="table-responsive mt-3"><table class="table table-sm align-middle mb-0"><caption class="caption-top">Filtered approved post totals by game</caption><thead><tr><th scope="col">Game</th><th scope="col" class="text-end">Posts</th></tr></thead><tbody>
      @forelse ($gameSummaries as $summary)<tr><th scope="row">{{ $summary['name'] }}</th><td class="text-end">{{ number_format($summary['posts_count']) }}</td></tr>@empty<tr><td colspan="2" class="text-secondary">No summary data available.</td></tr>@endforelse
    </tbody></table></div>
  </details>
</section>
