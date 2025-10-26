{{-- Name: HO YI VON --}}
{{-- Student ID : 23WMR14542 --}}

@extends('layouts.admin')

@section('title', 'Movies')

@section('content')
    <h1>Manage Movies</h1>
    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary mb-3">Add New Movie</a>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.movies.panel') }}" style="margin-bottom: 20px;">
        <input type="text" name="title" placeholder="Search Title" value="{{ request('title') }}">

        <select name="genre">
            <option value="">-- Genre --</option>
            <option value="action" @selected(request('genre') == 'action')>Action</option>
            <option value="comedy" @selected(request('genre') == 'comedy')>Comedy</option>
            <option value="drama" @selected(request('genre') == 'drama')>Drama</option>
            <option value="horror" @selected(request('genre') == 'horror')>Horror</option>
            <option value="romance" @selected(request('genre') == 'romance')>Romance</option>
        </select>

        <select name="status">
            <option value="">-- Status --</option>
            <option value="coming_soon" @selected(request('status') == 'coming_soon')>Coming Soon</option>
            <option value="now_showing" @selected(request('status') == 'now_showing')>Now Showing</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
            <option value="re_released" @selected(request('status') == 're_released')>Re-Released</option>
        </select>

        <select name="popular">
            <option value="">-- Popular --</option>
            <option value="1" @selected(request('popular') == '1')>Popular</option>
            <option value="0" @selected(request('popular') == '0')>Not Popular</option>
        </select>

        <select name="sort">
            <option value="">Sort by Title</option>
            <option value="asc" @selected(request('sort') == 'asc')>Ascending</option>
            <option value="desc" @selected(request('sort') == 'desc')>Descending</option>
        </select>

        <button type="submit">Filter</button>
        <a href="{{ route('admin.movies.panel') }}" style="margin-left: 10px;">Reset</a>
    </form>


    <table class="table table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Genre</th>
                <th>Director</th>
                <th>Status</th>
                <th>Popular</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($movies as $movie)
            <tr>
                <td>{{ $movie->title }}</td>
                <td>{{ $movie->genre }}</td>
                <td>{{ $movie->director }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $movie->status)) }}</td>
                <td>
                    @if ($movie->is_popular)
                        <span class="badge bg-success">
                            <i class="fas fa-star"></i> Popular
                        </span>
                    @else
                        <span class="badge bg-secondary">Not Popular</span>
                    @endif
                </td>
                <td>
                    @if (!in_array($movie->status, ['now_showing', 're_released']))
                        <form action="{{ route('admin.movies.activate', $movie) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-success btn-sm" onclick="return confirm('Activate this movie?')" style="min-width: 85px;">Activate</button>
                        </form>
                    @else
                        <form action="{{ route('admin.movies.deactivate', $movie) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-warning btn-sm" onclick="return confirm('Deactivate this movie?')" style="min-width: 85px;">Deactivate</button>
                        </form>
                    @endif
                    @if (!$movie->is_popular)
                        <form action="{{ route('admin.movies.popular.add', $movie) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-primary btn-sm" onclick="return confirm('Mark this movie as popular?')" style="min-width: 135px;">Mark as Popular</button>
                        </form>
                    @else
                        <form action="{{ route('admin.movies.popular.remove', $movie) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-secondary btn-sm" onclick="return confirm('Unmark this movie as popular?')" style="min-width: 135px;">Unmark as Popular</button>
                        </form>
                    @endif

                    <a href="{{ route('admin.movies.edit', $movie) }}">Edit</a> 
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $movies->links('pagination::bootstrap-5') }}
    </div>

@endsection