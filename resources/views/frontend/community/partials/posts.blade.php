@forelse ($posts as $index => $post)
  <article class="v5-thread v5-search-result">
    <div class="v5-thread-index">{{ str_pad((string) ($posts->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}</div>
    <div>
      <div class="v5-thread-meta"><b>{{ $post->game?->name ?? 'Game unavailable' }}</b><time datetime="{{ $post->created_at?->toIso8601String() }}">{{ $post->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</time></div>
      <h2><a href="{{ route('community.show', $post->id) }}">{{ $post->title }}</a></h2>
      <p>{{ \Illuminate\Support\Str::limit($post->body, 280) }}</p>
      <div class="v5-thread-footer"><span>By {{ $post->author?->name ?? 'Community member' }}</span><span>{{ $post->replies_count }} {{ \Illuminate\Support\Str::plural('reply', $post->replies_count) }}</span><a href="{{ route('community.show', $post->id) }}">Read discussion ↗</a></div>
    </div>
  </article>
@empty
  <div class="v5-empty-stream"><h2>No approved posts found</h2><p>Try another game or date range, or check back after a post is approved.</p></div>
@endforelse
<div class="mt-4">{{ $posts->links('pagination::bootstrap-5') }}</div>
