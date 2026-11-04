@extends('frontend.include.app')
@section('title', 'Guessing Community | ' . setting('title', 'GSK'))
@section('meta_description', 'Join game-specific discussions, read approved community posts and share your thoughts.')
@section('robots_content', $posts->total() ? 'index,follow' : 'noindex,follow')
@section('content')
<section class="gsk-community-hero"><div class="container py-5">
  <span class="gsk-kicker">👥 COMMUNITY SPACE</span><h1 class="mt-3">Guessing community.<br><span>Your voice. Your people.</span></h1>
  <p>Read game-wise discussions, share a respectful prediction and learn from the community.</p>
  <div class="d-flex flex-wrap gap-2"><a class="btn btn-warning rounded-pill px-4" href="{{ route('community.create') }}">✍️ Create a post</a><a class="btn btn-outline-light rounded-pill px-4" href="#community-feed">💬 Browse discussions</a></div>
  <div class="row g-2 mt-4"><div class="col-4"><div class="gsk-community-stat"><strong>{{ number_format($posts->total()) }}</strong><small>Total posts</small></div></div><div class="col-4"><div class="gsk-community-stat"><strong>{{ number_format($posts->sum('replies_count')) }}</strong><small>Replies on page</small></div></div><div class="col-4"><div class="gsk-community-stat"><strong>{{ $posts->count() }}</strong><small>Active threads</small></div></div></div>
</div></section>
<section class="container py-4 py-lg-5" id="community-feed">
  @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
  <div class="gsk-section-heading"><div><span>COMMUNITY FEED</span><h2>💬 Latest discussions</h2><p>Read posts and join the conversation.</p></div><span class="gsk-section-date">{{ number_format($posts->total()) }} posts</span></div>
  <div class="d-grid gap-3">
    @forelse ($posts as $post)
      <article class="gsk-community-post">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2"><span class="badge rounded-pill text-bg-light">🎮 {{ $post->game->name ?? 'Game discussion' }}</span><time class="small text-secondary" datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</time></div>
        <h2 class="h5 mt-3 mb-2"><a href="{{ route('community.show', $post->id) }}">{{ $post->title }}</a></h2>
        <p class="gsk-community-body">{{ \Illuminate\Support\Str::limit($post->body, 320) }}</p>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-top pt-3"><span class="small text-secondary">👤 {{ $post->author->name ?? 'Community member' }}</span><a href="{{ route('community.show', $post->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">💬 {{ $post->replies_count }} replies · Open thread →</a></div>
        @if ($post->replies->isNotEmpty())
          <div class="gsk-recent-replies mt-3"><strong class="small">Recent replies</strong>
            @foreach ($post->replies as $reply)
              <div class="gsk-recent-reply"><span class="gsk-reply-avatar">{{ mb_strtoupper(mb_substr($reply->author->name ?? 'U',0,1)) }}</span><div><b>{{ $reply->author->name ?? 'Community member' }}</b><p>{{ \Illuminate\Support\Str::limit($reply->body, 180) }}</p><small>{{ $reply->created_at->timezone(config('app.timezone'))->diffForHumans() }}</small></div></div>
            @endforeach
            <a class="small fw-bold" href="{{ route('community.show', $post->id) }}">View all replies →</a>
          </div>
        @endif
      </article>
    @empty
      <div class="gsk-empty-state"><span>💬</span><strong>No discussions yet</strong><p>Start the first discussion by choosing an active game.</p><a class="btn btn-primary rounded-pill mt-2" href="{{ route('community.create') }}">✍️ Create a post</a></div>
    @endforelse
  </div>
  @if ($posts->hasPages())<div class="d-flex justify-content-center mt-4">{{ $posts->links() }}</div>@endif
</section>
@endsection
