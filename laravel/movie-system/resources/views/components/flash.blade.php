{{-- resources/views/components/flash.blade.php --}}

{{--Name: CHONG CHEE WEE--}}
{{--Student ID: 2314523--}}

@php
  $hasMsg = session('success') || session('error') || session('warning') || session('info') || session('status') || $errors->any();
  $type   = session('error') || $errors->any() ? 'error'
         : (session('warning') ? 'warning'
         : (session('info') ? 'info' : 'success'));
  $msg = session('success') ?? session('error') ?? session('warning') ?? session('info') ?? session('status');
@endphp

@if ($hasMsg)
  <div id="flash"
       class="flash flash--{{ $type }}"
       role="status" aria-live="polite" aria-atomic="true">
    <div class="flash__icon" aria-hidden="true">
      @if($type==='success') ✓ @elseif($type==='error') ⚠ @elseif($type==='warning') ! @else ℹ @endif
    </div>
    <div class="flash__text">
      @if($msg)
        {{ $msg }}
      @elseif($errors->any())
        @if ($errors->count() === 1)
          {{ $errors->first() }}
        @else
          Please fix the highlighted fields.
        @endif
      @endif
    </div>
    <button id="flash-close" class="flash__close" aria-label="Close">×</button>
  </div>

  <style>
    .flash{
      position: fixed;
      top: 16px;
      left: 50%;
      transform: translateX(-50%);
      width: min(560px, calc(100% - 32px));
      padding: 12px 16px;
      border-radius: 10px;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 12px 30px rgba(0,0,0,.25);
      z-index: 9999; /* above any header */
      opacity: 0;
      translate: 0 -10px;
      transition: opacity .25s ease, translate .25s ease;
    }
    .flash--success { background: #10b981; }  /* green */
    .flash--error   { background: #ef4444; }  /* red */
    .flash--warning { background: #f59e0b; }  /* amber */
    .flash--info    { background: #3b82f6; }  /* blue */
    .flash__icon{ font-weight: 700; }
    .flash__close{
      margin-left: auto;
      background: none;
      border: 0;
      color: inherit;
      font-size: 18px;
      cursor: pointer;
      line-height: 1;
    }
    .flash--show{ opacity: 1; translate: 0 0; }
    .flash--hide{ opacity: 0; translate: 0 -10px; }
  </style>

  <script>
    (function () {
      const el = document.getElementById('flash');
      if (!el) return;
      // show with animation
      requestAnimationFrame(() => el.classList.add('flash--show'));

      const closeBtn = document.getElementById('flash-close');
      const hide = () => {
        el.classList.remove('flash--show');
        el.classList.add('flash--hide');
        setTimeout(() => el.remove(), 250);
      };
      // auto-hide after 3s
      const timer = setTimeout(hide, 3000);
      closeBtn?.addEventListener('click', () => { clearTimeout(timer); hide(); });
    })();
  </script>
@endif
