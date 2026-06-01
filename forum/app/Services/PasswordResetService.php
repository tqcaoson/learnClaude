<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PasswordResetService
{
    public function sendResetLink(string $email): void
    {
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            // Do not reveal whether the email exists
            return;
        }

        // Remove any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Log token since mail is not configured
        Log::info('Password reset token for ' . $email . ': ' . $token);
    }

    public function reset(string $email, string $token, string $password): bool
    {
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return false;
        }

        // Token expires after 60 minutes
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if ($createdAt->diffInMinutes(now()) > 60) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return false;
        }

        if (!Hash::check($token, $record->token)) {
            return false;
        }

        DB::table('users')->where('email', $email)->update([
            'password' => Hash::make($password),
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return true;
    }
}
