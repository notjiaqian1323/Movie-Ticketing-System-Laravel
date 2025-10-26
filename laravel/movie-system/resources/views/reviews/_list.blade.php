{{-- Name: CHONG CHEE WEE --}}
{{-- Student ID: 2314523 --}}

@php
  $items = $reviews['data'] ?? $reviews; 
  $back = $returnTo ?? url()->current();
@endphp

<div class="bg-gray-800 text-white rounded shadow review-card-dark border border-white">
  <div class="bg-gray-700 text-white font-semibold px-4 py-2 rounded-t">
    What people are saying
  </div>

  <div class="p-4">
    @forelse ($items as $rev)
@php
    $isArray   = is_array($rev);
    $rating    = (int) ($isArray ? ($rev['rating'] ?? 0) : ($rev->rating ?? 0));
    $full      = max(0, min(5, $rating));
    $empty     = 5 - $full;
    $comment   = $isArray ? ($rev['comment'] ?? null) : ($rev->comment ?? null);
    $dtRaw     = $isArray ? ($rev['reviewed_at'] ?? null) : ($rev->review_datetime ?? $rev->created_at ?? null);
    $when      = $dtRaw ? \Carbon\Carbon::parse($dtRaw)->timezone(config('app.timezone'))->format('Y-m-d H:i') : '';

    if ($isArray) {
        $reviewer = ($rev['is_anonymous'] ?? false)
            ? 'Anonymous'
            : ($rev['account']['username'] ?? 'User'); 
        $revId    = $rev['id'] ?? null;
        $owner    = auth()->check() && auth()->user()->role === 'customer' && ((int)$rev['account_id'] === (int)auth()->id());
    } else {
        // This part is for Reviews from the local database
        $reviewer = ($rev->is_anonymous ?? false)
            ? 'Anonymous'
            : ($rev->username ?? 'User'); 
        $revId = $rev->id ?? null;
        $owner = auth()->check() && auth()->user()->role === 'customer' && ((int)$rev->account_id === (int)auth()->id());
    }
@endphp

      <div class="pb-3 mb-3 border-b border-gray-700">
        <div class="flex justify-between items-center">
          <div class="text-yellow-400">
            {!! str_repeat('★', $full) !!}{!! str_repeat('☆', $empty) !!}
            <span class="text-gray-400 text-sm">({{ $rating }}/5)</span>
          </div>
          <small class="text-gray-400">{{ $when }}</small>
        </div>

        @if($comment)
          <p class="mt-2 mb-1">{{ $comment }}</p>
        @endif

        <div class="flex justify-between items-center">
          <small class="text-gray-400">by {{ $reviewer }}</small>

          @if($owner && $revId)
            <div class="flex gap-2">
              <a href="{{ route('reviews.edit', ['review' => $revId, 'return_to' => $back]) }}"
                 class="px-2 py-1 border border-blue-500 text-blue-500 text-sm rounded hover:bg-blue-500 hover:text-white transition">
                Edit
              </a>

              <form action="{{ route('reviews.destroy', $revId) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                @csrf @method('DELETE')
                <input type="hidden" name="return_to" value="{{ $back }}">
                <button type="submit" class="px-2 py-1 border border-red-500 text-red-500 text-sm rounded hover:bg-red-500 hover:text-white transition">
                  Delete
                </button>
              </form>
            </div>
          @endif
        </div>
      </div>
    @empty
      <p class="text-gray-400 mb-0">No reviews yet.</p>
    @endforelse
  </div>

  @if(isset($reviews) && method_exists($reviews, 'links'))
    <div class="px-3 pb-3">
      {{ $reviews->links() }}
    </div>
  @endif
</div>

<style>
  .bi-star, .bi-star-fill { vertical-align: -2px; }
</style>
