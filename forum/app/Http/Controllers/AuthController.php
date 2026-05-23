<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Exceptions\EmailNotVerifiedException;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private RegisterUserAction $registerAction,
        private LoginUserAction $loginAction,
        private AuthService $authService,
        private UserRepository $userRepository,
    ) {}

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $dto = RegisterDTO::fromRequest($request);
            $user = $this->registerAction->execute($dto);

            return redirect('/email/verify-sent')
                ->with('success', 'Registration successful! Please check your email to verify your account.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $dto = LoginDTO::fromRequest($request);
            $user = $this->loginAction->execute($dto);

            auth()->login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect('/posts')->with('success', 'Welcome back!');
        } catch (InvalidCredentialsException $e) {
            return back()->withInput(['email' => $request->email])->with('error', 'Invalid email or password');
        } catch (EmailNotVerifiedException $e) {
            return back()->withInput(['email' => $request->email])->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->withInput(['email' => $request->email])->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out');
    }

    public function verifyEmail(string $token)
    {
        if ($this->authService->verifyEmail($token)) {
            return redirect('/login')->with('success', 'Email verified successfully! You can now log in.');
        }

        return redirect('/login')->with('error', 'Invalid verification token');
    }

    public function verifyEmailSent(): View
    {
        return view('auth.verify-email-sent');
    }
}
