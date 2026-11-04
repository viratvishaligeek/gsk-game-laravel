@extends('frontend.include.app')
@section('title', $post->title . ' | Guessing Community')
@section('meta_description', \Illuminate\Support\Str::limit($post->body, 155))
@section('robots_content', 'index,follow')
@section('content')
<section class="gsk-page-hero"><div class="container py-4 py-lg-5"><nav aria-label="Breadcrumb"><ol class="breadcrumb mb-3"><li class="breadcrumb-item"><a href="{{ route('community.index') }}">Community</a></li><li class="breadcrumb-item active" aria-current="page">{{ $post->game->name }}</li></ol></nav><span class="gsk-kicker">🎮 {{ $post->game->name }}</span><h1 class="mt-3">{{ $post->title }}</h1><p class="mb-0">Posted by {{ $post->author->name }} · {{ $post->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</p></div></section>
<section class="container py-4 py-lg-5"><div class="gsk-community-form-card mx-auto">
  @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
  <article class="gsk-community-post-detail"><p>{{ $post->body }}</p></article>
  <div class="gsk-section-heading mt-4"><div><span>DISCUSSION</span><h2>💬 Replies ({{ $post->replies_count }})</h2></div></div>
  @forelse ($post->replies as $reply)<article class="gsk-reply-card"><div class="d-flex justify-content-between gap-2"><strong>{{ $reply->author->name }}</strong><small>{{ $reply->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</small></div><p>{{ $reply->body }}</p></article>@empty<div class="gsk-empty-state"><span>💬</span><strong>No replies yet</strong><p>Be the first to join the discussion.</p></div>@endforelse
  <form class="mt-4" method="POST" action="{{ route('community.reply', $post->id) }}">@csrf<label class="form-label fw-semibold" for="body">Add a reply</label><textarea class="form-control mb-2" id="body" name="body" rows="4" minlength="2" maxlength="2000" required>{{ old('body') }}</textarea>@error('body')<small class="text-danger">{{ $message }}</small>@enderror<button class="btn btn-primary rounded-pill px-4 mt-2" type="submit">Post reply →</button></form>
</div></section>
@endsection
