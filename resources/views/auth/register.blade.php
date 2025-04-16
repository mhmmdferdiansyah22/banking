@extends('layouts.app')

@section('content')
    <style>
        body {
            /* background: linear-gradient(135deg, #e3f2fd, #e3f2fd); */
            background: linear-gradient(135deg, #ffffff, #ffffff);
        }

        .container {
            max-width: 1200px;
        }

        .card-custom,
        .elegant-card {
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background: #fff;
            padding: 20px;
        }

        .card-custom:hover,
        .elegant-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        .user-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .user-card {
            text-align: center;
            padding: 15px;
            border-radius: 20px;
            background: #f8f9fa;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .user-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .user-image,
        .form-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .user-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .elegant-card,
            .card-custom {
                padding: 15px;
            }

            .user-card {
                padding: 10px;
            }

            .form-image,
            .user-image {
                width: 80px;
                height: 80px;
            }
        }

        .swal2-popup {
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        }

        .swal2-title {
            font-size: 22px;
            font-weight: bold;
        }

        .swal2-icon {
            animation: tada 1s ease-in-out;
        }
    </style>

    <div class="container" style="margin-top: 80px">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card elegant-card text-center">
                    <div class="card-header">{{ __('Register') }}</div>
                    <div class="card-body register-card-body">
                        <img src="https://i.pinimg.com/736x/75/29/93/7529939a5645116bfbd9044ed049ff37.jpg" class="form-image"
                            alt="User">
                        @if (session('success'))
                            <script>
                                Swal.fire({
                                    title: "🎉 Sukses!",
                                    text: "{{ session('success') }}",
                                    icon: "success",
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                            </script>
                        @endif
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name" required
                                    autofocus>
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>

                            <div class="form-group">
                                <label for="role_id">{{ __('Select Role') }}</label>
                                <select name="role_id" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        @if (
                                            (Auth::user()->role->name == 'admin' && in_array($role->name, ['bank', 'student'])) ||
                                                (Auth::user()->role->name == 'bank' && $role->name == 'student'))
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-gradient btn-block">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="card card-custom">
                    <div class="card-header">Registered Users</div>
                    <div class="card-body">
                        <div class="user-list">
                            @foreach ($users as $user)
                                <div class="user-card">
                                    <img src="https://i.pinimg.com/736x/75/29/93/7529939a5645116bfbd9044ed049ff37.jpg"
                                        class="user-image" alt="User">
                                    <h6>{{ $user->name }}</h6>
                                    <p>{{ $user->email }}</p>
                                    <p>{{ $user->role->name ?? 'No Role' }}</p>
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm delete-btn"
                                                data-user="{{ $user->name }}">
                                                Delete
                                            </button>
                                        </form>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                            Edit
                                        </a>
                                    </div>
                                    @if (session('error'))
                                        <script>
                                            Swal.fire({
                                                title: "⚠️ Gagal!",
                                                text: "{{ session('error') }}",
                                                icon: "error",
                                                timer: 3000,
                                                timerProgressBar: true,
                                                showConfirmButton: false
                                            });
                                        </script>
                                    @endif
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            document.querySelectorAll(".delete-btn").forEach(button => {
                                                button.addEventListener("click", function(event) {
                                                    event.preventDefault(); // Mencegah form langsung terkirim

                                                    let userName = this.getAttribute("data-user");

                                                    Swal.fire({
                                                        title: "⚠️ Apakah Anda Yakin?",
                                                        html: "<b>Akun " + userName + " akan dihapus secara permanen!</b>",
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#d33",
                                                        cancelButtonColor: "#3085d6",
                                                        confirmButtonText: "🗑️ Ya, hapus!",
                                                        cancelButtonText: "❌ Batal"
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            this.closest("form")
                                                                .submit(); // Kirim form hanya jika dikonfirmasi
                                                        }
                                                    });
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
