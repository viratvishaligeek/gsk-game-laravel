<section class="card mb-4" aria-labelledby="community-game-summary-title">
    <div class="card-body">
        <h2 class="h5" id="community-game-summary-title">Approved posts by game</h2>
        <p class="text-body-secondary small">This summary follows the selected game and date filters.</p>
        @forelse ($gameSummaries as $summary)
            <div class="mb-3">
                <div class="d-flex justify-content-between gap-3 mb-1"><span>{{ $summary['name'] }}</span><strong>{{ number_format($summary['posts_count']) }}</strong></div>
                <div class="progress" role="progressbar" aria-label="{{ $summary['name'] }} approved posts" aria-valuenow="{{ $summary['posts_count'] }}" aria-valuemin="0" aria-valuemax="{{ $maxPosts }}">
                    <div class="progress-bar" style="width: {{ min(100, (int) round(($summary['posts_count'] / $maxPosts) * 100)) }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-body-secondary mb-0">No approved posts match the selected filters.</p>
        @endforelse
        <div class="table-responsive mt-4">
            <table class="table table-sm align-middle mb-0">
                <caption class="caption-top">Filtered approved post totals by game</caption>
                <thead><tr><th scope="col">Game</th><th scope="col" class="text-end">Posts</th></tr></thead>
                <tbody>
                    @forelse ($gameSummaries as $summary)
                        <tr><th scope="row">{{ $summary['name'] }}</th><td class="text-end">{{ number_format($summary['posts_count']) }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="text-body-secondary">No summary data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
