@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header text-center">
                <h4 class="mb-0">🔑 Forgot Password</h4>
            </div>
            <div class="card-body">
                <p class="text-muted text-center mb-4">Enter your email address and we will send you a password reset link.</p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    </div>
                </form>

                <div class="text-center">
                    <a href="{{ route('auth.login') }}" class="text-decoration-none">← Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
