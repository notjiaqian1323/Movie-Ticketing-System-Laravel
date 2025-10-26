{{-- Name: CHONG CHEE WEE --}}
{{-- Student ID: 2314523 --}}

@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="container mx-auto my-30 page-dark">

  <div class="flex justify-between items-center mb-3">
    <h2 class="text-xl font-semibold text-white mb-0">My Reviews</h2>

    {{-- Sort form --}}
    <form id="sortForm" method="GET" class="flex items-center gap-2">
      <label for="sort" class="text-gray-400 text-sm mb-0">Sort:</label>
      <select name="sort" id="sort" class="bg-gray-700 text-white border border-gray-600 rounded px-2 py-1 text-sm">
        <option value="new"  {{ ($sort ?? request('sort', 'new')) === 'new' ? 'selected' : '' }}>Newest</option>
        <option value="old"  {{ ($sort ?? request('sort')) === 'old' ? 'selected' : '' }}>Oldest</option>
        <option value="high" {{ ($sort ?? request('sort')) === 'high' ? 'selected' : '' }}>Highest rating</option>
        <option value="low"  {{ ($sort ?? request('sort')) === 'low' ? 'selected' : '' }}>Lowest rating</option>
      </select>
    </form>
  </div>

  @forelse($reviews as $rev)
    @php
        $movie = $rev['movie'] ?? null;
        $title = $movie['title'] ?? ($rev->movie_title ?? 'Movie');
        $poster = isset($movie['image_path']) && $movie['image_path']
            ? asset('storage/movies/' . $movie->image_path)
            : asset('images/default-movie.jpg');
        $rating = (int) ($rev['rating'] ?? 0);
        $comment = $rev['comment'] ?? null;
        $anonymous = (bool) ($rev['is_anonymous']?? false);
        $dt   = $rev['review_datetime'] ?? $rev->created_at ?? null;
        $when = $dt? \Carbon\Carbon::parse($dt)->timezone(config('app.timezone'))->format('Y-m-d H:i'): '';
        $movieId = $movie['id'] ?? null;
        $revId = $rev['id'] ?? null;
        $full = max(0, min(5, $rating));
        $empty = 5 - $full;
        $returnTo = route('reviews.history', request()->only('sort', 'page'));
    @endphp

    <div class="bg-gray-800 text-white rounded shadow mb-3">
      <div class="flex flex-col md:flex-row">
        <div class="md:w-1/8">
          <a href="{{ $movieId ? route('movies.show', $movieId) : '#' }}" class="block h-full">
            <img src="{{ $poster }}" alt="{{ $title }}" class="w-full h-full object-cover min-h-[140px] rounded-l">
          </a>
        </div>
        <div class="md:w-7/8">
          <div class="p-4">
            <div class="flex justify-between items-start">
              <div>
                <h5 class="text-lg font-semibold mb-1">
                  <a href="{{ $movieId ? route('movies.show', $movieId) : '#' }}" class="hover:underline text-white">
                    {{ $title }}
                  </a>
                </h5>
                <div class="flex items-center mb-1">
                  @for ($i = 1; $i <= 5; $i++)
                    @if($i <= $full)
                      <span class="text-yellow-400 text-lg">★</span>
                    @else
                      <span class="text-gray-500 text-lg">☆</span>
                    @endif
                  @endfor
                  <span class="text-gray-400 text-sm ml-2">({{ $rating }}/5)</span>
                </div>
                @if($anonymous)
                  <span class="bg-gray-600 text-gray-200 text-xs px-2 py-1 rounded">Anonymous</span>
                @endif
              </div>

              <small class="text-gray-400">{{ $when }}</small>
            </div>

            @if($comment)
              <p class="mt-2 mb-1">{{ $comment }}</p>
            @endif

            <div class="mt-2 flex gap-2">
              <a href="{{ $revId ? route('reviews.edit', ['review' => $revId, 'return_to' => $returnTo]) : '#' }}"
                 class="px-3 py-1 border border-blue-500 text-blue-500 text-sm rounded hover:bg-blue-500 hover:text-white transition">
                Edit
              </a>

              @if($revId)
                <form action="{{ route('reviews.destroy', $revId) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                  @csrf @method('DELETE')
                  <input type="hidden" name="return_to" value="{{ $returnTo }}">
                  <button type="submit" class="px-3 py-1 border border-red-500 text-red-500 text-sm rounded hover:bg-red-500 hover:text-white transition">
                    Delete
                  </button>
                </form>
              @else
                <button type="button" class="px-3 py-1 border border-red-500 text-red-500 text-sm rounded cursor-not-allowed">Delete</button>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="bg-yellow-100 text-yellow-900 px-4 py-3 rounded">You haven’t written any reviews yet.</div>
  @endforelse

  @if(isset($reviews) && method_exists($reviews, 'links'))
    <div class="mt-3">
      {{ $reviews->appends(request()->only('sort'))->links() }}
    </div>
  @endif
</div>

<style>
  .bi-star, .bi-star-fill { vertical-align: -2px; }
</style>

<script>
  document.getElementById('sort')?.addEventListener('change', () => {
    document.getElementById('sortForm').submit();
  });
</script>
@endsection
