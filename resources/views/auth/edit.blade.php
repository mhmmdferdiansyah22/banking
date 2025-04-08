@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h4>Edit User</h4>
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <input type="text" name="name" class="form-control mb-2" value="{{ $user->name }}" required>
                    <input type="email" name="email" class="form-control mb-2" value="{{ $user->email }}" required>

                    <select name="role_id" class="form-control mb-2" required>
                        @foreach ($roles as $role)
                            @if (
                                (Auth::user()->role->name == 'admin' && in_array($role->name, ['bank', 'student'])) ||
                                    (Auth::user()->role->name == 'bank' && $role->name == 'student'))
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>

                    <input type="password" name="password" class="form-control mb-2" placeholder="New Password (optional)">

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>

            </div>
        </div>
    </div>
@endsection
