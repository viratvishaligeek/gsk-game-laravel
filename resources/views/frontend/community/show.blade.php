@extends('frontend.include.app')
@section('title', $post->title . ' | Guessing Community')
@section('meta_description', \Illuminate\Support\Str::limit($post->body, 155))
@section('robots_content', 'index,follow')
@section('content')
<section class="gsk-v3-community-hero gsk-v3-community-detail-hero">
  <div class="container py-4 py-lg-5"><nav aria-label="Breadcrumb"><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="{{ route('community.index') }}">Community</a></li><li class="breadcrumb-item active" aria-current="page">{{ $post->game->name }}</li></ol></nav>
    <span class="gsk-v3-kicker">🎮 {{ $post->game->name }}</span><h1>{{ $post->title }}</h1>
    <p>Posted by {{ $post->author->name }} · {{ $post->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</p>
    <div class="gsk-v3-community-actions"><a class="btn btn-outline-light" href="{{ route('community.index') }}">← All discussions</a><a class="btn btn-warning" href="{{ route('community.create') }}">✍️ New post</a></div>
  </div>
</section>
<section class="container py-4 py-lg-5">
  <div class="gsk-v3-discussion-workspace mx-auto">
    @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    <article class="gsk-v3-discussion-body"><span>ORIGINAL POST</span><p>{{ $post->body }}</p></article>
    <div class="gsk-v3-replies-heading"><div><span>COMMUNITY CONVERSATION</span><h2>💬 Replies</h2></div><strong>{{ $post->replies_count }}</strong></div>
    @forelse ($post->replies as $reply)
      <article class="gsk-v3-reply"><div class="gsk-v3-reply-avatar">{{ mb_strtoupper(mb_substr($reply->author->name ?? 'U',0,1)) }}</div><div><div class="gsk-v3-reply-meta"><strong>{{ $reply->author->name }}</strong><small>{{ $reply->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</small></div><p>{{ $reply->body }}</p></div></article>
    @empty
      <div class="gsk-v3-empty"><span>💬</span><strong>No replies yet</strong><p>Be the first to join the discussion.</p></div>
    @endforelse
    <form class="gsk-v3-reply-form" method="POST" action="{{ route('community.reply', $post->id) }}">@csrf
      <label class="form-label" for="body">Add your reply</label><textarea class="form-control" id="body" name="body" rows="4" minlength="2" maxlength="2000" required>{{ old('body') }}</textarea>
      @error('body')<small class="text-danger">{{ $message }}</small>@enderror
      <button class="btn btn-primary rounded-pill px-4 mt-3" type="submit">Post reply →</button>
    </form>
  </div>
</section>
@endsectionn
