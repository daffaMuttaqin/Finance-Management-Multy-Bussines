<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Link google_id if email match but no google_id
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'provider' => 'google',
                    ]);
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user, true);

            // Audit
            AuditService::log(
                $user->businesses()->first()?->id ?? 1,
                $user->id,
                'LOGIN_GOOGLE',
                'user',
                (string)$user->id,
                null,
                ['email' => $user->email]
            );

            // If no business, redirect to setup wizard
            if ($user->businesses()->count() === 0 && \App\Models\Business::count() === 0) {
                return redirect()->route('setup');
            }

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google: '.$e->getMessage()]);
        }
    }
}
