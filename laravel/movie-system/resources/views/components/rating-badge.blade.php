{{-- Name: CHONG CHEE WEE --}}
{{-- Student ID: 2314523 --}}
@props(['value' => 0, 'count' => 0])
@php $val = number_format((float)($value ?? 0), 1); @endphp

<div class="position-absolute bottom-0 end-0 mb-2 me-2 px-2 py-1 rounded-pill bg-dark bg-opacity-75 text-warning small d-flex align-items-center gap-1 rating-badge">
  <span aria-hidden="true">★</span>
  <span>{{ $val }}</span>
  <span class="text-light">({{ $count }})</span>
</div>
