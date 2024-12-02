<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;

class KeycloakAuthController extends Controller
{
    public function login() {
        return Socialite::driver('keycloak')->scopes(['email', 'profile'])->redirect();
    }

    public function callback() {
        try {
            $ssoUser = Socialite::driver('keycloak')->user();
            
            if(User::where('email', $ssoUser->getEmail())->exists()) {
                $appUser = User::where('email', $ssoUser->getEmail())->first();
                auth()->login($appUser);
            } else {
                $createUser = new CreateNewUser();
                $appUser = $createUser->create([
                    'name' => $ssoUser->getName(),
                    'email' => $ssoUser->getEmail(),
                    'provider' => "keycloak",
                    'terms' => 'accepted'
                ]);
                auth()->login($appUser);
            }

            return redirect()->route('home');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('login')->with('error', 'Authentication failed!');
        }
    }
}
