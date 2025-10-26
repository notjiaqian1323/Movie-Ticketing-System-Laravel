{{--
Name: CHONG KA HONG
Student ID: 2314524
--}}
@extends('layouts.app')

@section('title', 'Unauthorized')

<!-- havent test this page  -->


@section('content')
    <div class="container my-5">
        <h2>Unauthorized</h2>
        <div class="alert alert-danger">You do not have permission to access this page.</div>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
        @if (!Auth::check())
            <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
        @endif
    </div>
@endsection