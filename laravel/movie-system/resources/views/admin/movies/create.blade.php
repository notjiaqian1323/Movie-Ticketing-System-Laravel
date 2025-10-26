{{-- Name: HO YI VON --}}
{{-- Student ID : 23WMR14542 --}}

@extends('layouts.admin')

@section('title', 'Create Movie')

@section('content')
<div class="container my-5">
    <h2>Create Movie</h2>
    <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" 
                class="form-control @error('title') is-invalid @enderror" 
                value="{{ old('title') }}" 
                required maxlength="100">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Genre --}}
        <div class="mb-3">
            <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
            <input type="text" name="genre" id="genre" 
                class="form-control @error('genre') is-invalid @enderror" 
                value="{{ old('genre') }}" 
                required maxlength="50">
            @error('genre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Director --}}
        <div class="mb-3">
            <label for="director" class="form-label">Director <span class="text-danger">*</span></label>
            <input type="text" name="director" id="director" 
                class="form-control @error('director') is-invalid @enderror" 
                value="{{ old('director') }}" 
                required maxlength="255">
            @error('director')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Cast --}}
        <div class="mb-3">
            <label for="cast" class="form-label">Cast <span class="text-danger">*</span></label>
            <textarea name="cast" id="cast" 
                class="form-control @error('cast') is-invalid @enderror" 
                rows="2" required>{{ old('cast') }}</textarea>
            @error('cast')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Synopsis --}}
        <div class="mb-3">
            <label for="synopsis" class="form-label">Synopsis</label>
            <textarea name="synopsis" id="synopsis" 
                class="form-control @error('synopsis') is-invalid @enderror" 
                rows="4" maxlength="2000">{{ old('synopsis') }}</textarea>
            @error('synopsis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Duration --}}
        <div class="mb-3">
            <label for="duration" class="form-label">Duration (minutes)</label>
            <input type="number" name="duration" id="duration" 
                class="form-control @error('duration') is-invalid @enderror" 
                value="{{ old('duration') }}" 
                min="1" max="500">
            @error('duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Language --}}
        <div class="mb-3">
            <label for="language" class="form-label">Language</label>
            <input type="text" name="language" id="language" 
                class="form-control @error('language') is-invalid @enderror" 
                value="{{ old('language') }}" 
                maxlength="50">
            @error('language')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Subtitles --}}
        <div class="mb-3">
            <label for="subtitles" class="form-label">Subtitles</label>
            <input type="text" name="subtitles" id="subtitles" 
                class="form-control @error('subtitles') is-invalid @enderror" 
                value="{{ old('subtitles') }}" 
                maxlength="50">
            @error('subtitles')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Age Rating --}}
        <div class="mb-3">
            <label for="age_rating" class="form-label">Age Rating</label>
            <input type="text" name="age_rating" id="age_rating" 
                class="form-control @error('age_rating') is-invalid @enderror" 
                value="{{ old('age_rating') }}" 
                maxlength="10" placeholder="e.g. PG-13">
            @error('age_rating')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" 
                class="form-control @error('status') is-invalid @enderror" 
                required>
                <option value="">-- Select Status --</option>
                <option value="coming_soon" {{ old('status') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                <option value="now_showing" {{ old('status') == 'now_showing' ? 'selected' : '' }}>Now Showing</option>
                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                <option value="re_released" {{ old('status') == 're_released' ? 'selected' : '' }}>Re-released</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Release Date --}}
        <div class="mb-3">
            <label for="release_date" class="form-label">Release Date <span class="text-danger">*</span></label>
            <input type="date" name="release_date" id="release_date" 
                class="form-control @error('release_date') is-invalid @enderror" 
                value="{{ old('release_date') }}" 
                required>
            @error('release_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Poster Image --}}
        <div class="mb-3">
            <label for="image_path" class="form-label">Poster Image</label>
            <input type="file" name="image_path" id="image_path" 
                class="form-control @error('image_path') is-invalid @enderror" 
                accept="image/*">
            @error('image_path')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Popular --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_popular" id="is_popular" 
                class="form-check-input" {{ old('is_popular') ? 'checked' : '' }}>
            <label for="is_popular" class="form-check-label">Mark as Popular</label>
        </div>

        <button type="submit" class="btn btn-primary">Create Movie</button>
    </form>
</div>
@endsection
