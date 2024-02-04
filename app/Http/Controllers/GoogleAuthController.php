<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Sanctum\Sanctum;


class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user_google = Socialite::driver('google')->stateless()->user();
        
        $userGoogleId = $user_google->id;

        $usuario = User::where('googleId', $userGoogleId)->first();

        if(!$usuario){
            $newUser = User::create([
                'name' => $user_google->user['given_name'],
                'lastName' => $user_google->user['family_name'],
                'email' => $user_google->email,
                'fotoPerfil' => $user_google->avatar,
                'googleId' => $user_google->id
            ]);

            $userId = $newUser->id;
            Auth::login($newUser);
            $user = Auth::User();
            $token = $user->createToken('token')->plainTextToken;
            return redirect("http://localhost:8080/google/login/$userId/$token ");
        }else{
            $userId = $usuario->id;
            Auth::login($usuario);
            $user = Auth::User();
            $token = $user->createToken('token')->plainTextToken;
            return redirect("http://localhost:8080/google/login/$userId/$token");
        }
        
    }
}
