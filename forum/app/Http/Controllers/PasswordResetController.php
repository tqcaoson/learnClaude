<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function __construct(
        private PasswordResetService $passwordResetService,
    ) {}

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $this->passwordResetService->sendResetLink($request->input('email'));

        return back()->with('success', 'If an account with that email exists, a reset link has been logged. Please check the application logs.');
    }

    public function showResetForm(Request $request, string $token)
    {
        $email = $request->query('email', '');

        return view('auth.reset-password', compact('token', 'email'));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $success = $this->passwordResetService->reset(
            $request->input('email'),
            $request->input('token'),
            $request->input('password'),
        );

        if (!$success) {
            return back()->withInput()->with('error', 'The password reset token is invalid or has expired.');
        }

        return redirect()->route('auth.login')
            ->with('success', 'Password reset successfully! Please log in with your new password.');
    }
}
