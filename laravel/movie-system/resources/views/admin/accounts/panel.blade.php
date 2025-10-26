{{--
Name: CHONG KA HONG
Student ID: 2314524
--}}
@extends('layouts.admin')

@section('title', 'Accounts')

@section('content')
    <div class="container my-5">
        <h2>Manage Users</h2>

        <!-- @if (session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mb-3">
                        {{ session('error') }}
                    </div>
                @endif -->

        <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary mb-3">Create New User</a>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.accounts.panel') }}" class="mb-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="username" placeholder="Search Username" value="{{ request('username') }}"
                        class="form-control">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">-- Status --</option>
                        <option value="active" @selected(request('status') == 'active')>Active</option>
                        <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-control">
                        <option value="">-- Role --</option>
                        <option value="customer" @selected(request('role') == 'customer')>Customer</option>
                        <option value="admin" @selected(request('role') == 'admin')>Admin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-control">
                        <option value="">Sort by Username</option>
                        <option value="asc" @selected(request('sort') == 'asc')>Ascending</option>
                        <option value="desc" @selected(request('sort') == 'desc')>Descending</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.accounts.panel') }}" class="btn btn-secondary ms-2">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($accounts as $account)
                    <tr>
                        <td>{{ $account->id }}</td>
                        <td>{{ $account->username }}</td>
                        <td>{{ $account->email }}</td>
                        <td>{{ ucfirst($account->role) }}</td>
                        <td>{{ ucfirst($account->status) }}</td>
                        <td>{{ $account->gender ?? '-' }}</td>
                        <td>{{ $account->date_of_birth ? $account->date_of_birth->format('Y-m-d') : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.accounts.edit', $account->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('admin.accounts.toggleStatus', $account->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PUT')
                                @if ($account->status === 'active')
                                    <button type="submit" class="btn btn-warning btn-sm"
                                        onclick="return confirm('Deactivate this user?')"
                                        style="min-width: 85px;">Deactivate</button>
                                @else
                                    <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Activate this user?')" style="min-width: 85px;">Activate</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $accounts->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection