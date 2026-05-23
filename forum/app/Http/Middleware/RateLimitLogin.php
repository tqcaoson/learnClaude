<?php

namespace App\Http\Middleware;

use App\Models\LoginAttempt;
use Closure;
use Illuminate\Http\Request;

class RateLimitLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Only apply to POST requests
        if ($request->getMethod() !== 'POST') {
            return $next($request);
        }

        $ip = $request->ip() ?? '0.0.0.0';
        $email = $request->input('email', '');

        // Check if account is locked
        $attempt = LoginAttempt::where('email', $email)
            ->where('ip_address', $ip)
            ->first();

        if ($attempt && $attempt->locked_until && now()->isBefore($attempt->locked_until)) {
            return redirect()->back()
                ->withInput(['email' => $email])
                ->with('error', 'Too many login attempts. Please try again after ' . $attempt->locked_until->diffForHumans());
        }

        // Process the request
        $response = $next($request);

        // If login was successful (response redirects to /posts)
        if ($response->status() === 302 && str_contains($response->headers->get('Location'), '/posts')) {
            // Reset login attempts
            if ($attempt) {
                $attempt->delete();
            }
            return $response;
        }

        // Handle failed login attempts
        if ($response->status() === 302 || $response->status() === 302) {
            if (!$attempt) {
                LoginAttempt::create([
                    'email' => $email,
                    'ip_address' => $ip,
                    'attempts' => 1,
                ]);
            } else {
                $attempt->increment('attempts');

                // Lock account after 5 failed attempts
                if ($attempt->attempts >= 5) {
                    $attempt->update([
                        'locked_until' => now()->addMinutes(15),
                    ]);

                    return redirect()->back()
                        ->withInput(['email' => $email])
                        ->with('error', 'Account locked due to too many failed login attempts. Please try again in 15 minutes.');
                }
            }
        }

        return $response;
    }
}
