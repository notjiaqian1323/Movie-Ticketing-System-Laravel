{{-- Name: HO YI VON --}}
{{-- Student ID : 23WMR14542 --}}

@extends('layouts.admin')

@section('title', 'Edit Movie: {{ $movie->title }}')

@section('content')
<div class="container my-5">
    <h2>Edit Movie: {{ $movie->title }}</h2>

    @if ($userRole === 'admin')
        <form method="POST" action="{{ route('admin.movies.update', $movie->id) }}" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" 
                    class="form-control @error('title') is-invalid @enderror" 
                    value="{{ old('title', $movie->title) }}" 
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
                    value="{{ old('genre', $movie->genre) }}" 
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
                    value="{{ old('director', $movie->director) }}" 
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
                    rows="2" required>{{ old('cast', $movie->cast) }}</textarea>
                @error('cast')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Synopsis --}}
            <div class="mb-3">
                <label for="synopsis" class="form-label">Synopsis</label>
                <textarea name="synopsis" id="synopsis" 
                    class="form-control @error('synopsis') is-invalid @enderror" 
                    rows="4" maxlength="2000">{{ old('synopsis', $movie->synopsis) }}</textarea>
                @error('synopsis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Duration --}}
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (minutes)</label>
                <input type="number" name="duration" id="duration" 
                    class="form-control @error('duration') is-invalid @enderror" 
                    value="{{ old('duration', $movie->duration) }}" 
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
                    value="{{ old('language', $movie->language) }}" maxlength="50">
                @error('language')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Subtitles --}}
            <div class="mb-3">
                <label for="subtitles" class="form-label">Subtitles</label>
                <input type="text" name="subtitles" id="subtitles" 
                    class="form-control @error('subtitles') is-invalid @enderror" 
                    value="{{ old('subtitles', $movie->subtitles) }}" maxlength="50">
                @error('subtitles')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Age Rating --}}
            <div class="mb-3">
                <label for="age_rating" class="form-label">Age Rating</label>
                <input type="text" name="age_rating" id="age_rating" 
                    class="form-control @error('age_rating') is-invalid @enderror" 
                    value="{{ old('age_rating', $movie->age_rating) }}" maxlength="10" placeholder="e.g. PG-13">
                @error('age_rating')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    @foreach(['coming_soon', 'now_showing', 'archived', 're_released'] as $status)
                        <option value="{{ $status }}" {{ $movie->status === $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
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
                    value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required>
                @error('release_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Poster Image --}}
            <div class="mb-3">
                <label for="image_path" class="form-label">Poster Image</label>
                <input type="file" name="image_path" id="image_path" 
                    class="form-control @error('image_path') is-invalid @enderror" accept="image/*">
                @error('image_path')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if ($movie->image_path)
                    <div class="mt-2">
                        <img src="{{ $movie->image_url }}" alt="Current Image" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            {{-- Popular --}}
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_popular" id="is_popular" class="form-check-input" {{ $movie->is_popular ? 'checked' : '' }}>
                <label for="is_popular" class="form-check-label">Mark as Popular</label>
            </div>

            <button type="submit" class="btn btn-primary">Update Movie</button>
        </form>
    @else
        <div class="alert alert-danger">Access denied. Admin privileges required.</div>
    @endif
</div>
@endsection
