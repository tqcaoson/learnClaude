<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case USER = 'user';
    case MODERATOR = 'moderator';
    case ADMIN = 'admin';
}
