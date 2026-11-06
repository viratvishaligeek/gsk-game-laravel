@extends('frontend.include.app')
@section('title', $post->title . ' | Guessing Community')
@section('meta_description', \Illuminate\Support\Str::limit($post->body, 155))
@section('robots_content', 'index,follow')
@section('content')
    <header class="v6-page-hero">
        <div class="v6-wrap">
            <nav class="v6-breadcrumb"><a href="{{ route('community.index') }}">Community</a> / {{ $post->game->name }}</nav>
            <span class="v6-eyebrow eyebrow-purple">COMMUNITY DISCUSSION</span>
            <h1>{{ $post->title }}</h1>
            <p>Posted by {{ $post->author->name }} ·
                {{ $post->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</p>
        </div>
    </header>
    <section class="v6-wrap v6-discussion-layout">
        <div>
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            <article class="v6-post-panel">
                <span class="v6-eyebrow eyebrow-blue">ORIGINAL POST · {{ $post->game->name }}</span>
                <h1>{{ $post->title }}</h1>
                <p>{{ $post->body }}</p>
            </article>
            <section class="v6-replies-panel">
                <header class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 fw-bold mb-0">Replies</h2><span class="v6-count-chip">{{ $post->replies_count }}</span>
                </header>
                @forelse ($post->replies as $reply)
                    <article class="v6-reply">
                        <div class="v6-reply-avatar">{{ mb_strtoupper(mb_substr($reply->author->name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <div class="d-flex flex-wrap justify-content-between gap-2"><strong
                                    class="small">{{ $reply->author->name }}</strong><small
                                    class="text-secondary">{{ $reply->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</small>
                            </div>
                            <p>{{ $reply->body }}</p>
                        </div>
                </article>@empty<div class="v6-empty"><strong>No replies yet</strong>
                        <p>Be the first to join the discussion.</p>
                    </div>
                @endforelse
            </section>
        </div>
        <aside class="v6-reply-panel"><span class="v6-eyebrow eyebrow-cyan">YOUR TURN</span>
            <h2>Add a reply</h2>
            <p class="small text-secondary">Keep replies respectful and relevant to the topic.</p>
            <form method="POST" action="{{ route('community.reply', $post->id) }}">@csrf<label
                    class="form-label small fw-bold" for="body">Your reply</label>
                <textarea class="form-control" id="body" name="body" rows="6" minlength="2" maxlength="2000" required>{{ old('body') }}</textarea>
                @error('body')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <button class="v6-btn v6-btn-blue w-100 mt-3" type="submit">Post reply ↗</button>
            </form><a class="d-block mt-3 small fw-bold" href="{{ route('community.index') }}">← All discussions</a><a
                class="d-block mt-2 small fw-bold" href="{{ route('community.create') }}">Start a new post ↗</a>
        </aside>
    </section>

@endsection
