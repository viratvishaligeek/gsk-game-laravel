@extends('frontend.include.app')
@section('title', $post->title . ' | Guessing Community')
@section('meta_description', \Illuminate\Support\Str::limit($post->body, 155))
@section('robots_content', 'index,follow')
@section('content')
<header class="v5-page-hero"><div class="v5-page-hero-inner"><div><nav class="v5-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('community.index') }}">Community</a> / {{ $post->game->name }}</nav><span class="v5-kicker mt-4">DISCUSSION / {{ $post->game->name }}</span><h1>{{ $post->title }}</h1><p>Posted by {{ $post->author->name }} · {{ $post->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</p></div><aside class="v5-page-aside"><strong class="fs-2">{{ $post->replies_count }}</strong><br>community replies</aside></div></header>
<section class="v5-discussion-layout">
  <section>
    @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    <article class="v5-post-body"><span class="v5-kicker">ORIGINAL POST</span><p class="mt-3 mb-0">{{ $post->body }}</p></article>
    <section class="v5-replies"><header class="d-flex justify-content-between align-items-end mb-3"><div><span class="v5-kicker">THE CONVERSATION</span><h2 class="h3 fw-bold mb-0">Replies</h2></div><strong>{{ $post->replies_count }}</strong></header>
      @forelse ($post->replies as $reply)
        <article class="v5-reply"><div class="v5-reply-avatar">{{ mb_strtoupper(mb_substr($reply->author->name ?? 'U',0,1)) }}</div><div><div class="d-flex flex-wrap justify-content-between gap-2"><strong>{{ $reply->author->name }}</strong><small class="text-secondary">{{ $reply->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</small></div><p>{{ $reply->body }}</p></div></article>
      @empty
        <div class="gsk-v3-empty"><strong>No replies yet</strong><p>Be the first to join the discussion.</p></div>
      @endforelse
    </section>
  </section>
  <aside class="v5-reply-compose">
    <span class="v5-kicker">YOUR VOICE</span><h2 class="h4 fw-bold mt-2">Add to the conversation.</h2><p class="small text-white-50">Keep replies respectful and relevant to the topic.</p>
    <form method="POST" action="{{ route('community.reply', $post->id) }}">@csrf
      <label class="form-label" for="body">Your reply</label><textarea class="form-control" id="body" name="body" rows="6" minlength="2" maxlength="2000" required>{{ old('body') }}</textarea>
      @error('body')<small class="text-warning">{{ $message }}</small>@enderror
      <button class="v5-button v5-button-gold w-100 mt-3" type="submit">Post reply ↗</button>
    </form>
    <a class="d-block mt-4 text-white small" href="{{ route('community.index') }}">← All discussions</a>
    <a class="d-block mt-2 text-white small" href="{{ route('community.create') }}">Start a new post ↗</a>
  </aside>
</section>
@endsection
