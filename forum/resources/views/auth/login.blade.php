@extends('layouts.guest')

@section('title', 'Login - Community Forum')

@section('subtitle', 'Welcome back! Sign in to your account')

@section('content')
    <form method="POST" action="{{ route('auth.login.submit') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">📧 Email Address</label>
            <input 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="you@example.com" 
                required 
                autofocus
            >
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">🔐 Password</label>
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
                name="password" 
                placeholder="Enter your password" 
                required
            >
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-check">
                <input 
                    type="checkbox" 
                    class="form-check-input" 
                    id="remember" 
                    name="remember" 
                    value="1"
                >
                <label class="form-check-label" for="remember">
                    ✅ Remember me for 30 days
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">🔓 Login</button>

        <div class="auth-link">
            Don't have an account? <a href="{{ route('auth.register') }}">Create one now</a>
        </div>
    </form>
@endsection
