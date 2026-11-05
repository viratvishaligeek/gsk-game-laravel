@extends('frontend.include.app')
@section('title', 'Create Guessing Post | ' . setting('title', 'GSK'))
@section('robots_content', 'noindex,follow')
@section('content')
<header class="v5-page-hero"><div class="v5-page-hero-inner"><div><span class="v5-kicker">COMMUNITY STUDIO / NEW THREAD</span><h1>Start with a good question.</h1><p>Choose a game topic, write a clear title and give other members enough context to respond.</p></div><aside class="v5-page-aside"><a class="text-white" href="{{ route('community.index') }}">← Return to discussions</a></aside></div></header>
<main class="v5-form-layout">
  <aside class="v5-form-intro"><span class="v5-kicker">BEFORE YOU POST</span><h2>Make it useful.</h2><p class="text-secondary small">Choose the most relevant game, give the thread a specific title and include the details that will help other members understand your message.</p><ol class="small text-secondary ps-3"><li class="mb-2">Select a topic</li><li class="mb-2">Write a clear title</li><li>Explain your message</li></ol></aside>
  <section class="v5-form-sheet">
    @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('community.store') }}">@csrf
      <div class="mb-4"><label class="form-label" for="game_id">01 / Game topic</label><select class="form-select" id="game_id" name="game_id" required><option value="">Select an active game</option>@foreach ($games as $game)<option value="{{ $game->id }}" {{ (string) old('game_id') === (string) $game->id ? 'selected' : '' }}>{{ $game->name }}</option>@endforeach</select>@error('game_id')<small class="text-danger">{{ $message }}</small>@enderror</div>
      <div class="mb-4"><label class="form-label" for="title">02 / Discussion title</label><input class="form-control" id="title" name="title" type="text" maxlength="120" minlength="4" value="{{ old('title') }}" placeholder="What would you like to discuss?" required>@error('title')<small class="text-danger">{{ $message }}</small>@enderror</div>
      <div class="mb-4"><label class="form-label" for="body">03 / Your message</label><textarea class="form-control" id="body" name="body" rows="7" minlength="10" maxlength="5000" placeholder="Add the context that will help others understand your post…" required>{{ old('body') }}</textarea><small class="form-text">10–5,000 characters. HTML is not supported.</small>@error('body')<small class="text-danger d-block">{{ $message }}</small>@enderror</div>
      <div class="alert alert-warning rounded-0 small">Posts may require administrator approval before appearing publicly.</div>
      <button class="v5-button v5-button-gold" type="submit">Submit discussion ↗</button>
    </form>
  </section>
</main>
@endsection
