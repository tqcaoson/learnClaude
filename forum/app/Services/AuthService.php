<?php

namespace App\Services;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
use App\Exceptions\EmailNotVerifiedException;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function register(RegisterDTO $dto): User
    {
        if ($this->userRepository->emailExists($dto->email)) {
            throw new \Exception('Email already registered');
        }

        $user = $this->userRepository->create([
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'name' => $dto->name ?? $dto->email,
            'email_verification_token' => Str::random(60),
        ]);

        // Log verification token for MVP (in production, send via email)
        \Log::info("Email verification token for {$dto->email}: {$user->email_verification_token}");

        return $user;
    }

    public function login(LoginDTO $dto): User
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        if (!$user->isEmailVerified()) {
            throw new EmailNotVerifiedException('Please verify your email before logging in. Check your inbox for the verification link.');
        }

        return $user;
    }

    public function verifyEmail(string $token): bool
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return false;
        }

        $this->userRepository->update($user, [
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        return true;
    }
}
