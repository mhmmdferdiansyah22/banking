<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=dev ice-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
    
        /* Existing styles here... */
    
        /* Add the new keyframe animations */
        @keyframes slide-in-left {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    
        .image-slide {
            animation: slide-in-left 0.5s ease forwards;
        }
    
        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .col-md-10 {
                flex-direction: column; /* Stack the elements vertically */
                height: 100vh; /* Set height to full viewport */
                overflow: hidden; /* Disable scrolling */
            }
    
            .image-slide {
                width: 100%; /* Take full width */
                height: 40%; /* Occupy 40% of the viewport height */
                display: flex;
                justify-content: center;
                align-items: center;
                animation: slide-in-left 0.7s ease forwards; /* Apply animation for mobile */
            }
    
            .image-slide img {
                max-width: 80%; /* Adjust the size to fit better on mobile */
                height: auto; /* Maintain aspect ratio */
            }
    
            .form-slide {
                width: 100%; /* Ensure form takes full width */
                height: 40%; /* Occupy 60% of the viewport height */
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 20px; /* Adjust padding for better spacing */
                animation: none; /* No animation for form on mobile */
            }
    
            body {
                overflow: hidden; /* Disable scrolling on the body */
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="min-height: 100vh; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center;">
        <div class="row justify-content-center">
            <div class="col-md-10" style="display: flex; background: #fff; border-radius: 10px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <!-- Left side illustration -->
                <div class="col-md-6 d-flex justify-content-center align-items-center image-slide" style="background-color: transparent; position: relative; padding: 0;">
                    <img src="https://i.pinimg.com/736x/7f/a6/bf/7fa6bf1abb86a09f49bc232797a75895.jpg" alt="Login Illustration" style="width: 115%; height: auto; object-fit: contain; margin-left: 20px; margin-top: 5px;">
                </div>

                <!-- Right side login form -->
                <div class="col-md-6 d-flex flex-column justify-content-center align-items-start p-5 form-slide">
                    <h2 class="mb-4">{{ __('Welcome') }}</h2>
                    <p class="mb-4">{{ __('Sign in to continue') }}</p>

                    <form method="POST" action="{{ route('login') }}" style="width: 100%;">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary w-100" style="background-color: #2457a3; border: none;">
                                {{ __('Sign In') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>