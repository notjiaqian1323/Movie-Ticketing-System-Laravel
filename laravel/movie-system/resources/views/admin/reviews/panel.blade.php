{{-- Name: CHONG CHEE WEE --}}
{{-- Student ID: 2314523 --}}

@extends('layouts.admin')
@section('title', 'Reviews')
@section('content')
    <div class="container">
        <h1 class="mb-4">Manage Reviews</h1>

        {{-- Filter / Search Form --}}
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="user" value="{{ request('user') }}" class="form-control" placeholder="Search by User Name">
                </div>
                <div class="col-md-3">
                    <input type="text" name="movie" value="{{ request('movie') }}" class="form-control" placeholder="Search by Movie Title">
                </div>
                <div class="col-md-2">
                    <select name="rating" class="form-control">
                        <option value="">All Ratings</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} Stars
                            </option>
                        @endfor
                    </select>
                </div>
                {{-- NEW: include anonymous toggle --}}
                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check ms-2">
                        <input class="form-check-input" type="checkbox" id="include_anon" name="include_anon" value="1" {{ request('include_anon') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="include_anon">Include anonymous</label>
                    </div>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        {{-- Reviews Table --}}
        <div class="card mb-5">
            <div class="card-header">All Reviews</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Movie</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            {{-- USER --}}
                            <td>
                                @if($review->is_anonymous)
                                    <span class="badge bg-secondary">Anonymous</span>
                                @elseif($review->account)
                                    <a href="mailto:{{ $review->account->email }}" class="text-decoration-none">
                                        {{ $review->account->username }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>

                            {{-- MOVIE --}}
                            <td>{{ $review->movie->title ?? 'N/A' }}</td>

                            {{-- RATING --}}
                            <td class="text-nowrap align-middle">
                                {{ $review->rating }} <span aria-hidden="true">⭐</span>
                                <span class="visually-hidden">stars</span>
                            </td>

                            <td>{{ \Illuminate\Support\Str::limit($review->comment, 50) }}</td>

                            {{-- DATE (prefer review_datetime, fallback to created_at) --}}
                            @php
                                $dt = $review->review_datetime ?? $review->created_at ?? null;
                                $when = $dt
                                    ? \Carbon\Carbon::parse($dt)
                                        ->timezone(config('app.timezone'))
                                        ->format('Y-m-d H:i')
                                    : '';
                            @endphp
                            <td>{{ $when }}</td>

                            {{-- ACTIONS --}}
                            <td class="text-nowrap">
                                @if($review->movie)
                                    <a href="{{ route('movies.show', $review->movie->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                        View
                                    </a>
                                @endif

                                {{-- Delete is still allowed for admins --}}
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this review?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No reviews found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $reviews->onEachSide(1)->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
