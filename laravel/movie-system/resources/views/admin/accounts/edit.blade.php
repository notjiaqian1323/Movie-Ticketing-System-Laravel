{{--
Name: CHONG KA HONG
Student ID: 2314524
--}}
@extends('layouts.admin')

@section('title', 'Edit User: {{ $account->username }}')

@section('content')
    <div class="container my-5">
        <h2>Edit User: {{ $account->username }}</h2>

        <!-- @if (session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif -->

        <form method="POST" action="{{ route('admin.accounts.update', $account->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $account->phone) }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth"
                    class="form-control @error('date_of_birth') is-invalid @enderror"
                    value="{{ old('date_of_birth', $account->date_of_birth ? $account->date_of_birth->format('Y-m-d') : '') }}">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username"
                    class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username', $account->username) }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $account->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <small>(Leave blank if no change)</small></label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror">
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
                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                    <option value="" {{ old('gender', $account->gender) == '' ? 'selected' : '' }}>Select Gender</option>
                    <option value="M" {{ old('gender', $account->gender) == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ old('gender', $account->gender) == 'F' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                    <option value="customer" {{ old('role', $account->role) == 'customer' ? 'selected' : '' }} @if ($account->role == 'admin') disabled @endif>Customer</option>
                    <option value="admin" {{ old('role', $account->role) == 'admin' ? 'selected' : '' }} @if ($account->role == 'customer') disabled @endif>Admin</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="active" {{ old('status', $account->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $account->status) == 'inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
@endsection