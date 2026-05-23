@extends('layouts.guest')

@section('title', 'Register - Community Forum')

@section('subtitle', 'Join our community and start sharing knowledge')

@section('content')
    <form method="POST" action="{{ route('auth.register.post') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">👤 Full Name</label>
            <input 
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                id="name" 
                name="name" 
                value="{{ old('name') }}" 
                placeholder="John Doe"
            >
            @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

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
                placeholder="Enter a strong password" 
                required
            >
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-muted d-block mt-1">
                ✓ Minimum 8 characters with mix of letters and numbers
            </small>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">✓ Confirm Password</label>
            <input 
                type="password" 
                class="form-control @error('password_confirmation') is-invalid @enderror" 
                id="password_confirmation" 
                name="password_confirmation" 
                placeholder="Repeat your password" 
                required
            >
            @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">🚀 Create Account</button>

        <div class="auth-link">
            Already have an account? <a href="{{ route('auth.login') }}">Login here</a>
        </div>
    </form>
@endsection
