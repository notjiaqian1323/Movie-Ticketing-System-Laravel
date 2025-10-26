<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
@extends('layouts.booking')

@section('title', $movie["title"])

@section('content')

@if ($movie)

<livewire:booking-multi-step :schedules="$schedule" :movie="$movie" :date="$date" />

@else
    <x-loading />
@endif
@endsection
