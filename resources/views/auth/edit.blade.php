@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 70px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow rounded-3">
                    <div class="card-header text-white" style="background: #1e3a8a;">
                        <h5 class="mb-0">Edit User</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="role_id" class="form-label">Role</label>
                                <select name="role_id" id="role_id" class="form-select" required>
                                    @foreach ($roles as $role)
                                        @if (
                                            (Auth::user()->role->name == 'admin' && in_array($role->name, ['bank', 'student'])) ||
                                                (Auth::user()->role->name == 'bank' && $role->name == 'student'))
                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    class="form-control" 
                                    placeholder="Leave blank to keep current password">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn text-white" style="background: #1e3a8a;">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection