@extends('backend.include.layout')

@section('page-header')
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="image"></i></div>
                            {{ $pageName }}
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-secondary" href="{{ route('admin.banner.index') }}">
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
        <div class="card shadow-sm mb-4">
            <div class="card-header">Add New Banner</div>
            <div class="card-body">
                <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row gx-3 mb-3">
                        <div class="col-md-8 mb-3">
                            <label class="small mb-1" for="name">Banner Title / Name <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" id="name" name="name" type="text"
                                placeholder="e.g. Festival Offer Banner" value="{{ old('name') }}" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="small mb-1" for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="small mb-1" for="destinationUrl">Banner URL <span class="text-danger">*</span></label>
                            <input class="form-control" id="destinationUrl" name="destinationUrl" type="text"
                                placeholder="e.g. Destination Url" value="{{ old('destinationUrl') }}" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="small mb-1" for="app_screen">App Screen <span class="text-danger">*</span></label>
                            <select class="form-select" id="app_screen" name="app_screen">
                                <option value="top" {{ old('app_screen', 'top') === 'top' ? 'selected' : '' }}>Top
                                </option>
                                <option value="bottom" {{ old('app_screen') === 'bottom' ? 'selected' : '' }}>Bottom
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small mb-1" for="image">Banner Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*"
                            onchange="previewImage(event)">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 d-none" id="imagePreviewContainer">
                        <label class="small mb-1 d-block">Preview:</label>
                        <img id="imagePreview" src="#" alt="Banner Preview" class="img-thumbnail rounded"
                            style="max-height: 180px;">
                    </div>
                    <button class="btn btn-primary" type="submit">Save Banner</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImage = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
