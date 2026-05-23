@extends('layouts.guest')

@section('title', 'Verify Your Email - Community Forum')

@section('content')
    <div class="alert alert-info">
        <h4>Check Your Email</h4>
        <p>We've sent a verification link to <strong>{{ $email ?? 'your email address' }}</strong>.</p>
        <p>Click the link in the email to activate your account.</p>
        <p class="small text-muted">If you don't see the email, check your spam folder or try registering again.</p>
    </div>

    @if(config('app.debug'))
        <div class="alert alert-warning">
            <small><strong>Development Mode:</strong> Use the token from logs to verify manually.</small>
        </div>
    @endif

    <p class="text-center">
        <a href="{{ route('auth.login') }}" class="btn btn-secondary">Back to Login</a>
    </p>
@endsection
