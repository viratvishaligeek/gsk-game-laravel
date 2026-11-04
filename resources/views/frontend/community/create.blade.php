@extends('frontend.include.layout')

@section('meta_title', 'Create Community Post | ' . app(\App\Services\AppSettingsService::class)->value('title', config('app.name')))
@section('meta_robots', 'noindex,follow')

@section('content')
<section class="section"><div class="" style="max-width:760px">
    <p><a href="{{ route('community.index') }}">← Back to community</a></p>
    <div class="card shadow-sm"><div class="card-body p-4">
        <h1 class="h3">Create a community post</h1>
        <p class="text-body-secondary">Posts may require administrator approval before they appear publicly.</p>
        @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('community.store') }}">@csrf
            <div class="mb-3"><label class="form-label" for="game_id">Game</label><select class="form-select @error('game_id') is-invalid @enderror" id="game_id" name="game_id" required><option value="">Choose a game</option>@foreach ($games as $game)<option value="{{ $game->id }}" @selected((string)old('game_id') === (string)$game->id)>{{ $game->name }}</option>@endforeach</select>@error('game_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="title">Title</label><input class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" minlength="4" maxlength="120" required>@error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="body">Post</label><textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="7" minlength="10" maxlength="5000" required>{{ old('body') }}</textarea>@error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <button class="btn btn-primary" type="submit">Submit post</button>
        </form>
    </div></div>
</div></section>
@endsection
