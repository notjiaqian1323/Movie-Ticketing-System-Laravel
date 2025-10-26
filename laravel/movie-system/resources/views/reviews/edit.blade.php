{{-- Name: CHONG CHEE WEE --}}
{{-- Student ID: 2314523 --}}

@extends('layouts.app')
@section('title','Edit Review')

@section('content')
<div class="container mx-auto py-50">
  <h2 class="text-2xl md:text-3xl font-semibold mb-6 text-white">Edit Review</h2>

  <div class="bg-gray-800 text-white rounded-lg shadow">
    <div class="p-6 space-y-6 text-base md:text-lg leading-relaxed">
      <form method="POST" action="{{ route('reviews.update', $review) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="return_to" value="{{ request('return_to', route('reviews.history')) }}">

        {{-- Rating --}}
        <div>
          <label class="block text-sm md:text-base font-medium mb-2 text-gray-200">Your Rating</label>

          <div class="flex justify-start">
            <div class="star-group inline-flex flex-row-reverse gap-1.5" aria-label="Rating out of 5">
              @for($i = 5; $i >= 1; $i--)
                <input
                  class="star-input hidden"
                  type="radio"
                  id="star-{{ $i }}"
                  name="rating"
                  value="{{ $i }}"
                  @checked(old('rating', (int)$review->rating) === $i)
                >
                <label for="star-{{ $i }}" class="star text-2xl md:text-3xl leading-none select-none">★</label>
              @endfor
            </div>
          </div>

          @error('rating')
            <p class="mt-2 text-sm md:text-base text-red-400">{{ $message }}</p>
          @enderror
        </div>

        {{-- Comment --}}
        <div>
          <label for="comment" class="block text-sm md:text-base font-medium mb-2 text-gray-200">Your Review</label>
          <textarea
            id="comment"
            name="comment"
            rows="5"
            class="w-full rounded-md bg-gray-700 text-white placeholder-gray-400 border border-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-500/30 px-4 py-3 text-base md:text-lg"
            placeholder="Write your thoughts..."
          >{{ old('comment', $review->comment) }}</textarea>
          @error('comment')
            <p class="mt-2 text-sm md:text-base text-red-400">{{ $message }}</p>
          @enderror
        </div>

        {{-- Anonymous --}}
        <div class="flex items-center gap-2">
          <input
            id="is_anonymous"
            name="is_anonymous"
            type="checkbox"
            value="1"
            class="h-4 w-4 md:h-5 md:w-5 rounded border-gray-500 bg-gray-700 text-blue-600 focus:ring-blue-500"
            @checked(old('is_anonymous', (bool) $review->is_anonymous))
          >
          <label for="is_anonymous" class="text-base md:text-lg">Post as Anonymous</label>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex items-center gap-3">
          <button
            type="submit"
            class="px-5 py-2.5 rounded-md bg-blue-600 text-white text-base md:text-lg font-medium hover:bg-blue-700 transition"
          >
            Save changes
          </button>

          <a
            href="{{ request('return_to', route('reviews.history')) }}"
            class="px-5 py-2.5 rounded-md bg-gray-600 text-white text-base md:text-lg font-medium hover:bg-gray-500 transition"
          >
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>


<style>
  .star-group .star { color: #4b5563; cursor: pointer; transition: color .15s ease; }
  .star-group .star:hover,
  .star-group .star:hover ~ .star,
  .star-group .star-input:checked ~ .star {
    color: #fbbf24; 
  }
</style>
@endsection
