<?php

namespace App\Actions\Auth;

use App\DTOs\RegisterDTO;
use App\Models\User;
use App\Services\AuthService;

class RegisterUserAction
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function execute(RegisterDTO $dto): User
    {
        return $this->authService->register($dto);
    }
}
