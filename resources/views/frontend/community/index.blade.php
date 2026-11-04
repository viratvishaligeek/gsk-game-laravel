@extends('frontend.include.app')
@section('title', 'Guessing Community | ' . setting('title', 'GSK'))
@section('meta_description', 'Join game-specific discussions, read approved community posts and share your thoughts.')
@section('robots_content', $posts->total() ? 'index,follow' : 'noindex,follow')
@section('content')
<section class="gsk-v3-community-hero">
  <div class="container py-5"><div class="row align-items-center g-4">
    <div class="col-lg-7"><span class="gsk-v3-kicker"><span class="gsk-v3-live-dot"></span> COMMUNITY NETWORK</span>
      <h1>Good conversations<br><em>start here.</em></h1><p>Read game-wise discussions, share a respectful post and take part in the community feed.</p>
      <div class="gsk-v3-community-actions"><a class="btn btn-warning" href="{{ route('community.create') }}">✍️ Start a discussion <b>→</b></a><a class="btn btn-outline-light" href="{{ route('index') }}#markets">🎮 Browse markets</a></div>
    </div>
    <div class="col-lg-5"><div class="gsk-v3-community-stats">
      <div><strong>{{ number_format($posts->total()) }}</strong><small>Total posts</small></div>
      <div><strong>{{ number_format($posts->sum('replies_count')) }}</strong><small>Replies on this page</small></div>
      <div><strong>{{ $posts->count() }}</strong><small>Visible threads</small></div>
    </div></div>
  </div></div>
</section>
<section class="container py-4 py-lg-5" id="community-feed">
  @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
  <div class="gsk-v3-community-feed-head"><div><span>COMMUNITY FEED</span><h2>💬 Latest discussions</h2><p>Open a thread to read the full post and its replies.</p></div><span>{{ number_format($posts->total()) }} posts</span></div>
  <div class="gsk-v3-thread-list">
    @forelse ($posts as $post)
      <article class="gsk-v3-thread-card">
        <div class="gsk-v3-thread-meta"><span>🎮 {{ $post->game->name ?? 'Game discussion' }}</span><time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</time></div>
        <h2><a href="{{ route('community.show', $post->id) }}">{{ $post->title }}</a></h2>
        <p>{{ \Illuminate\Support\Str::limit($post->body, 320) }}</p>
        <div class="gsk-v3-thread-footer"><span>👤 {{ $post->author->name ?? 'Community member' }}</span><a href="{{ route('community.show', $post->id) }}">💬 {{ $post->replies_count }} replies <b>→</b></a></div>
        @if ($post->replies->isNotEmpty())
          <div class="gsk-v3-thread-replies"><strong>Recent replies</strong>
            @foreach ($post->replies as $reply)
              <div><span>{{ mb_strtoupper(mb_substr($reply->author->name ?? 'U',0,1)) }}</span><p><b>{{ $reply->author->name ?? 'Community member' }}</b> · {{ \Illuminate\Support\Str::limit($reply->body, 160) }}<small>{{ $reply->created_at->timezone(config('app.timezone'))->diffForHumans() }}</small></p></div>
            @endforeach
            <a href="{{ route('community.show', $post->id) }}">Read all replies →</a>
          </div>
        @endif
      </article>
    @empty
      <div class="gsk-v3-empty"><span>💬</span><strong>No discussions yet</strong><p>Start the first discussion by choosing an active game.</p><a class="btn btn-primary rounded-pill mt-2" href="{{ route('community.create') }}">✍️ Create a post</a></div>
    @endforelse
  </div>
  @if ($posts->hasPages())<div class="d-flex justify-content-center mt-4">{{ $posts->links() }}</div>@endif
</section>
@endsection
