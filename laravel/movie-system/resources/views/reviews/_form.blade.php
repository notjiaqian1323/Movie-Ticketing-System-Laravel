{{-- Name: CHONG CHEE WEE --}}
{{-- Student ID: 2314523 --}}
@props(['movie'])

<div class="bg-gray-800 text-white rounded shadow review-card-dark border border-white">
  <div class="bg-gray-700 text-white font-semibold px-4 py-2 rounded-t">
    Write a Review
  </div>

  <div class="card-body p-4 border border-white">
    <form id="review-create-form" method="POST" action="{{ route('reviews.store', $movie) }}">
      @csrf

      {{-- Stars (1–5) --}}
      <div class="mb-4">
        <label class="form-label font-semibold">Your Rating</label>

        <div class="flex flex-row-reverse justify-end gap-1 mt-1 star-group-wrapper">
          @for ($i = 5; $i >= 1; $i--)
            <input
              type="radio"
              name="rating"
              value="{{ $i }}"
              id="star{{ $i }}"
              class="star-input hidden"
              @checked(old('rating') == $i)
              @if($i === 1) required @endif  {{-- set required on one radio in the group --}}
            >
            <label for="star{{ $i }}" class="star cursor-pointer text-gray-400 text-2xl select-none transition-colors duration-150">★</label>
          @endfor
        </div>

        {{-- Custom client-side message (hidden until needed) --}}
        <p id="rating-error" class="text-red-500 text-sm mt-1 hidden">Please select a star rating.</p>

        @error('rating')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
        <small class="text-gray-400">Click a star from 1 to 5.</small>
      </div>

      {{-- Comment --}}
      <div class="mb-4">
        <label class="form-label font-semibold">Your Review</label>
        <textarea
          name="comment"
          rows="4"
          maxlength="2000"
          placeholder="Share your thoughts..."
          class="w-full px-3 py-2 rounded-md bg-white text-black border border-black focus:outline-none focus:ring-2 focus:ring-yellow-500"
        >{{ old('comment') }}</textarea>
        @error('comment')
          <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
        @enderror
      </div>

      {{-- Anonymous --}}
      <div class="form-check mb-4">
        <input type="checkbox" id="anonCheck" name="is_anonymous" value="1" class="form-check-input" @checked(old('is_anonymous'))>
        <label for="anonCheck" class="form-check-label text-gray-500">Post as Anonymous</label>
      </div>

      <button
        type="submit"
        data-submit
        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-md transition-colors opacity-60 cursor-not-allowed"
        disabled
      >
        Submit Review
      </button>

      @if ($errors->any())
        <div class="text-red-500 text-sm mt-2">{{ $errors->first() }}</div>
      @endif

      <noscript>
        <p class="text-yellow-400 text-sm mt-2">Note: Please select a rating before submitting.</p>
      </noscript>
    </form>
  </div>
</div>

{{-- Tailwind-friendly star CSS --}}
<style>
  .star:hover,
  .star:hover ~ .star {
    color: #ffc107 !important; /* hover color */
  }
  .star-input:checked ~ .star,
  .star-input:checked + .star,
  .star-input:checked + .star ~ .star {
    color: #ffc107 !important; /* checked color */
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('review-create-form');
  if (!form) return;

  const submitBtn = form.querySelector('[data-submit]');
  const errorMsg  = form.querySelector('#rating-error');
  const group     = form.querySelector('.star-group-wrapper');

  function hasRating() {
    return !!form.querySelector('input[name="rating"]:checked');
  }

  function updateButtonState() {
    const ok = hasRating();
    submitBtn.disabled = !ok;
    submitBtn.classList.toggle('opacity-60', !ok);
    submitBtn.classList.toggle('cursor-not-allowed', !ok);
    if (ok) errorMsg.classList.add('hidden');
  }

  // Enable/disable as user picks stars
  form.querySelectorAll('input[name="rating"]').forEach(radio => {
    radio.addEventListener('change', updateButtonState);
  });

  // Guard submit if no rating
  form.addEventListener('submit', function (e) {
    if (!hasRating()) {
      e.preventDefault();
      errorMsg.classList.remove('hidden');
      group.classList.add('ring-2', 'ring-red-500', 'rounded');
      setTimeout(() => group.classList.remove('ring-2', 'ring-red-500', 'rounded'), 1200);
    }
  });

  // Initial state (handles old('rating') after validation errors)
  updateButtonState();
});
</script>
