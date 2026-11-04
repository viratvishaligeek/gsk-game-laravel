@extends('backend.include.layout')

@section('page-header')
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="edit" class="text-primary"></i></div>
                            {{ $pageName }}
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-secondary" href="{{ route('admin.faqs.index') }}">
                            <i class="me-1" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="container-fluid px-4">
        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card mb-4">
                <div class="card-header">Edit FAQ Information</div>
                <div class="card-body">
                    <div class="row gx-3 mb-3">
                        <div class="col-md-12 mb-3">
                            <label class="small mb-1" for="question">Question <span class="text-danger">*</span></label>
                            <input class="form-control" id="question" name="question" type="text"
                                value="{{ old('question', $faq->question) }}" required />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small mb-1" for="platform">Available on <span class="text-danger">*</span></label>
                            <select class="form-select" id="platform" name="platform" required>
                                <option value="both" {{ old('platform', $faq->platform ?? 'both') === 'both' ? 'selected' : '' }}>Web and App</option>
                                <option value="web" {{ old('platform', $faq->platform ?? 'both') === 'web' ? 'selected' : '' }}>Web only</option>
                                <option value="app" {{ old('platform', $faq->platform ?? 'both') === 'app' ? 'selected' : '' }}>App only</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small mb-1" for="page_slug">Page slug (optional)</label>
                            <input class="form-control" id="page_slug" name="page_slug" type="text" maxlength="150" pattern="[A-Za-z0-9-]+" value="{{ old('page_slug', $faq->page_slug) }}">
                            <small class="text-muted">Leave blank for global FAQ, or enter a public page slug such as faq or about.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small mb-1" for="game_id">Game (optional)</label>
                            <select class="form-select" id="game_id" name="game_id"><option value="">Global / no specific game</option>@foreach ($games as $game)<option value="{{ $game->id }}" @selected((string)old('game_id', $faq->game_id) === (string)$game->id)>{{ $game->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small mb-1" for="sort_order">Display order</label>
                            <input class="form-control" id="sort_order" name="sort_order" type="number" min="0" max="100000" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check mt-4"><input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" {{ filter_var(old('is_active', $faq->is_active ?? true), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active on website</label></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small mb-1" for="answer">Answer <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="answer" name="answer" rows="5"
                                required>{{ old('answer', $faq->answer) }}</textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">
                        <i class="me-1" data-feather="save"></i> Update FAQ
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
