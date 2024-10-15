<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use  App\Models\User;



class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
    
            // Cek apakah user sudah terdaftar
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]
            );
    
            // Login user
            Auth::login($user, true);
    
            // Redirect ke halaman yang diinginkan
            return redirect('home', compact('posts','communities'));
        } catch (\Exception $e) {
            return redirect('login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}