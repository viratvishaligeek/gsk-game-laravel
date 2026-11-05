@extends('frontend.include.app')
@section('title', 'Guessing Community | ' . setting('title', 'GSK'))
@section('meta_description', 'Join game-specific discussions, read approved community posts and share your thoughts.')
@section('robots_content', $posts->total() ? 'index,follow' : 'noindex,follow')
@section('content')
<header class="v5-community-hero"><div class="v5-community-hero-inner">
  <div><span class="v5-kicker">COMMUNITY / OPEN DISCUSSION</span><h1>Ideas move the conversation.</h1><p>Read game-wise discussions, share a respectful post and take part in the community feed.</p><div class="d-flex flex-wrap gap-2 mt-4"><a class="v5-button v5-button-gold" href="{{ route('community.create') }}">Start a discussion ↗</a><a class="v5-button v5-button-glass" href="{{ route('index') }}#markets">Browse markets</a></div></div>
  <aside class="v5-community-stat"><span>VISIBLE COMMUNITY POSTS</span><strong>{{ number_format($posts->total()) }}</strong><span>{{ number_format($posts->sum('replies_count')) }} replies across this page</span></aside>
</div></header>
<section class="v5-community-layout" id="community-feed">
  <aside class="v5-community-sidebar"><span class="v5-kicker">COMMUNITY DESK</span><a href="{{ route('community.create') }}">＋ Write a discussion</a><a href="{{ route('index') }}#markets">Browse game topics</a><a href="{{ route('charts.index') }}">Explore result charts</a><p class="small text-secondary mt-3">Posts and replies are shown according to the community moderation settings.</p></aside>
  <section class="v5-community-feed" aria-label="Latest discussions">
    @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-end gap-3 mb-3"><div><span class="v5-kicker">THE LATEST</span><h2 class="h3 fw-bold mb-0">Discussion stream</h2></div><span class="small text-secondary">{{ number_format($posts->total()) }} posts</span></div>
    @forelse ($posts as $index => $post)
      <article class="v5-thread">
        <div class="v5-thread-index">{{ str_pad((string) ($posts->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}</div>
        <div>
          <div class="v5-thread-meta"><b>{{ $post->game->name ?? 'Game discussion' }}</b><time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</time></div>
          <h2><a href="{{ route('community.show', $post->id) }}">{{ $post->title }}</a></h2>
          <p>{{ \Illuminate\Support\Str::limit($post->body, 320) }}</p>
          <div class="v5-thread-footer"><span>By {{ $post->author->name ?? 'Community member' }}</span><a href="{{ route('community.show', $post->id) }}">{{ $post->replies_count }} replies · Open thread ↗</a></div>
          @if ($post->replies->isNotEmpty())
            <div class="v5-thread-replies"><strong>Recent replies</strong>
              @foreach ($post->replies as $reply)
                <p><b>{{ $reply->author->name ?? 'Community member' }}</b> · {{ \Illuminate\Support\Str::limit($reply->body, 160) }} <small>{{ $reply->created_at->timezone(config('app.timezone'))->diffForHumans() }}</small></p>
              @endforeach
              <a href="{{ route('community.show', $post->id) }}">Read the full conversation →</a>
            </div>
          @endif
        </div>
      </article>
    @empty
      <div class="gsk-v3-empty"><strong>No discussions yet</strong><p>Start the first discussion by choosing an active game.</p><a class="btn btn-primary mt-2" href="{{ route('community.create') }}">Create a post</a></div>
    @endforelse
    @if ($posts->hasPages())<div class="d-flex justify-content-center mt-4">{{ $posts->links() }}</div>@endif
  </section>
</section>
@endsection
