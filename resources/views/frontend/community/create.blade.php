@extends('frontend.include.app')
@section('title', 'Create Guessing Post | ' . setting('title', 'GSK'))
@section('robots_content', 'noindex,follow')
@section('content')
<section class="gsk-v3-community-hero gsk-v3-community-form-hero"><div class="container py-4 py-lg-5">
  <span class="gsk-v3-kicker">✍️ COMMUNITY STUDIO</span><h1>Create a discussion.</h1><p>Choose a topic, write a useful title and give the community enough context to respond.</p>
  <div class="gsk-v3-community-actions"><a class="btn btn-outline-light" href="{{ route('community.index') }}">← Back to discussions</a></div>
</div></section>
<section class="container py-4 py-lg-5"><div class="gsk-v3-compose-card mx-auto">
  <div class="gsk-v3-compose-steps"><div class="active"><span>01</span><b>Topic</b></div><i></i><div><span>02</span><b>Title</b></div><i></i><div><span>03</span><b>Message</b></div></div>
  @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('community.store') }}">@csrf
    <div class="mb-3"><label class="form-label" for="game_id">1. Choose a game topic</label><select class="form-select" id="game_id" name="game_id" required><option value="">Select an active game</option>@foreach ($games as $game)<option value="{{ $game->id }}" {{ (string) old('game_id') === (string) $game->id ? 'selected' : '' }}>{{ $game->name }}</option>@endforeach</select>@error('game_id')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <div class="mb-3"><label class="form-label" for="title">2. Give it a clear title</label><input class="form-control" id="title" name="title" type="text" maxlength="120" minlength="4" value="{{ old('title') }}" placeholder="What would you like to discuss?" required>@error('title')<small class="text-danger">{{ $message }}</small>@enderror</div>
    <div class="mb-3"><label class="form-label" for="body">3. Write your message</label><textarea class="form-control" id="body" name="body" rows="7" minlength="10" maxlength="5000" placeholder="Add the context that will help others understand your post…" required>{{ old('body') }}</textarea><small class="form-text">10–5,000 characters. HTML is not supported.</small>@error('body')<small class="text-danger d-block">{{ $message }}</small>@enderror</div>
    <div class="gsk-v3-compose-note">🛡️ Posts may require administrator approval before appearing publicly.</div>
    <button class="btn btn-primary rounded-pill px-4 mt-3" type="submit">Publish discussion request →</button>
  </form>
</div></section>
@endsection
