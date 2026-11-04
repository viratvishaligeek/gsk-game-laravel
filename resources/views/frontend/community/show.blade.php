@extends('frontend.include.layout')

@section('meta_title', $post->title . ' | Community')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->body), 155))
@section('og_type', 'article')

@section('content')
<section class="section"><div class="" style="max-width:900px">
    <p><a href="{{ route('community.index') }}">← Back to community</a></p>
    <article class="card shadow-sm mb-4"><div class="card-body p-4">
        <span class="badge text-bg-primary">{{ $post->game?->name ?? 'Game unavailable' }}</span>
        <h1 class="h2 mt-3">{{ $post->title }}</h1>
        <p class="small text-body-secondary">Posted by {{ $post->author?->name ?? 'Community member' }} · {{ $post->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</p>
        <div class="community-post-body">{{ $post->body }}</div>
    </div></article>
    <h2 class="h4">Replies ({{ $post->replies_count }})</h2>
    @forelse ($replies as $reply)
        <article class="card mb-3"><div class="card-body">
            <p class="mb-2">{{ $reply->body }}</p>
            <div class="small text-body-secondary">{{ $reply->author?->name ?? 'Community member' }} · {{ $reply->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</div>
        </div></article>
    @empty
        <p class="text-body-secondary">No replies yet.</p>
    @endforelse
    <div class="mt-3">{{ $replies->links('pagination::bootstrap-5') }}</div>
    @auth('web')
        <form class="card mt-4" method="POST" action="{{ route('community.reply', $post->id) }}"><div class="card-body">
            @csrf
            <label class="form-label" for="body">Add a reply</label>
            <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="4" maxlength="2000" required>{{ old('body') }}</textarea>
            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button class="btn btn-primary mt-3" type="submit">Post reply</button>
        </div></form>
    @else
        <p><a href="{{ route('community.login') }}">Sign in</a> to reply to this discussion.</p>
    @endauth
</div></section>
@endsection

@push('schema')
    @php
        $postSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'articleBody' => strip_tags((string) $post->body),
            'datePublished' => $post->created_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'mainEntityOfPage' => route('community.show', $post->id),
        ];
        if ($post->author?->name) {
            $postSchema['author'] = ['@type' => 'Person', 'name' => $post->author->name];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($postSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endpush
