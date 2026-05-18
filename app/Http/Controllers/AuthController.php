<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller {

    public function redirectToYandex() {
        return Socialite::driver('yandex')->redirect();
    }

    public function handleYandexCallback() {
        $user = Socialite::driver('yandex')->user();
        // $user->token содержит OAuth токен
        // Сохраните токен в БД для дальнейшей работы с API Диска
    }

    public function guest(Request $request)
    {
        // Если токен уже есть — вернуть текущего пользователя
        if ($request->user()) {
            return response()->json([
                'user' => $request->user(),
                'token' => $request->bearerToken()
            ]);
        }

        $uuid =  Str::uuid();
        // Создаём гостя
        $guest = User::create([
            'role' => RoleEnum::GUEST->value,
            'email' => $uuid . '@example.com',
            'uuid' => $uuid,
            'password' => Hash::make('secret'),
        ]);

        // Создаём токен
        $token = $guest->createToken('guest')->plainTextToken;

        return response()->json([
            'user' => $guest,
            'token' => $token
        ]);
    }

    public function upgrade(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => RoleEnum::USER->value
        ]);

        return response()->json(['user' => $user]);
    }
}

