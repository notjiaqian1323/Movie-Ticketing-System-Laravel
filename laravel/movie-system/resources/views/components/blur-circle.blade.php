<!--
Name: Wo Jia Qian
Student Id: 2314023
-->

@php
    $style = "top: " . ($top ?? 'auto') . "; " .
             "left: " . ($left ?? 'auto') . "; " .
             "right: " . ($right ?? 'auto') . "; " .
             "bottom: " . ($bottom ?? 'auto') . ";";
@endphp

<div class="absolute -z-50 h-58 w-58 aspect-square rounded-full bg-primary/30 blur-3xl"
     style="{{ $style }}">
</div>