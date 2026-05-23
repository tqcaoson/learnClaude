<?php

namespace App\Actions\Auth;

use App\DTOs\LoginDTO;
use App\Models\User;
use App\Services\AuthService;

class LoginUserAction
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function execute(LoginDTO $dto): User
    {
        return $this->authService->login($dto);
    }
}
