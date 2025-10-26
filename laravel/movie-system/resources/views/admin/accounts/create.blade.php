{{--
Name: CHONG KA HONG
Student ID: 2314524
--}}
@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
  <div class="container my-5">
    <h2>Create User</h2>

    <!-- @if (session('success'))
            <div class="alert alert-success mb-3">
              {{ session('success') }}
            </div>
          @endif -->

    <form method="POST" action="{{ route('admin.accounts.store') }}">
      @csrf
      <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
          value="{{ old('phone') }}" required>
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" id="date_of_birth"
          class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}"
          max="{{ \Carbon\Carbon::now()->subYears(13)->toDateString() }}" required>
        @error('date_of_birth')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
          value="{{ old('username') }}" required>
        @error('username')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
          value="{{ old('email') }}" required>
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
          required>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
      </div>
      <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
          <option value="" {{ old('gender') == '' ? 'selected' : '' }}>Select Gender</option>
          <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
          <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
        </select>
        @error('gender')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
          <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
          <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        @error('role')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
          <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary">Create User</button>
    </form>
  </div>
@endsection