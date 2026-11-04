@extends('frontend.include.app')
@section('title', 'Create Guessing Post | ' . setting('title', 'GSK'))
@section('robots_content', 'noindex,follow')
@section('content')
<section class="gsk-page-hero"><div class="container py-4 py-lg-5"><span class="gsk-kicker">✍️ COMMUNITY DISCUSSION</span><h1 class="mt-3">Create a post</h1><p class="mb-0">Choose an active game topic and write a clear, respectful post. Posts may require administrator approval.</p></div></section>
<section class="container py-4 py-lg-5"><div class="gsk-community-form-card mx-auto">
  @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('community.store') }}">@csrf
    <div class="mb-3"><label class="form-label fw-semibold" for="game_id">Game topic</label><select class="form-select" id="game_id" name="game_id" required><option value="">Select a game</option>@foreach ($games as $game)<option value="{{ $game->id }}" {{ (string) old('game_id') === (string) $game->id ? 'selected' : '' }}>{{ $game->name }}</option>@endforeach</select>@error('game_id')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <div class="mb-3"><label class="form-label fw-semibold" for="title">Post title</label><input class="form-control" id="title" name="title" type="text" maxlength="120" minlength="4" value="{{ old('title') }}" required>@error('title')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <div class="mb-3"><label class="form-label fw-semibold" for="body">Your post</label><textarea class="form-control" id="body" name="body" rows="7" minlength="10" maxlength="5000" required>{{ old('body') }}</textarea><small class="text-secondary">10–5,000 characters. HTML is not supported.</small>@error('body')<small class="text-danger d-block">{{ $message }}</small>@enderror</div>
    <button class="btn btn-primary rounded-pill px-4" type="submit">Submit post →</button>
  </form>
</div></section>
@endsection
