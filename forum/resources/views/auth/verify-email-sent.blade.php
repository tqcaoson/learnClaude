@extends('layouts.guest')

@section('title', 'Verify Email - Community Forum')

@section('content')
    <h1 class="auth-title">Check Your Email</h1>

    <div class="alert alert-info">
        <p><strong>Registration successful!</strong></p>
        <p>We've sent a verification link to your email address. Please click the link to verify your account before logging in.</p>
    </div>

    <p class="text-center mt-4">
        <a href="{{ route('auth.login') }}" class="btn btn-secondary">Back to Login</a>
    </p>
@endsection
