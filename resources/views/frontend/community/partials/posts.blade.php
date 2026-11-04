@forelse ($posts as $post)
    <article class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                <span class="badge text-bg-primary">{{ $post->game?->name ?? 'Game unavailable' }}</span>
                <time class="text-muted small" datetime="{{ $post->created_at?->toIso8601String() }}">{{ $post->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</time>
            </div>
            <h2 class="h5"><a href="{{ route('community.show', $post->id) }}" class="text-decoration-none">{{ $post->title }}</a></h2>
            <p class="text-body-secondary mb-3">{{ \Illuminate\Support\Str::limit($post->body, 280) }}</p>
            <div class="d-flex flex-wrap gap-3 small text-muted">
                <span>By {{ $post->author?->name ?? 'Community member' }}</span>
                <span>{{ $post->replies_count }} {{ \Illuminate\Support\Str::plural('reply', $post->replies_count) }}</span>
                <a href="{{ route('community.show', $post->id) }}">Read discussion</a>
            </div>
        </div>
    </article>
@empty
    <div class="card"><div class="card-body py-5 text-center">
        <h2 class="h5">No approved posts found</h2>
        <p class="text-body-secondary mb-0">Try another game or date range, or check back after a post is approved.</p>
    </div></div>
@endforelse
<div class="mt-4">{{ $posts->links('pagination::bootstrap-5') }}</div>
