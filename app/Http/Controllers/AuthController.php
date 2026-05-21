<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    private function createNewGuest()
    {
        $uuid = Str::uuid();

        $guest = User::create([
            'role' => RoleEnum::GUEST->value,
            'email' => $uuid . '@guest.local',
            'uuid' => $uuid,
            'password' => Hash::make('secret'),
        ]);

        Auth::guard('web')->login($guest);


        return response()->json([
            'user' => $guest,
            'restored' => true,
            'session_id' => session()->getId(),
        ]);
    }


    /**
     * Гостевая авторизация через Sanctum Session Auth
     */
    public function guest(Request $request)
    {
        // Уже авторизован
        if (Auth::check()) {

            return response()->json([
                'user' => $request->user(),
            ]);
        }

        // UUID гостя из cookie
        $guestUuid = $request->cookie('guest_uuid');

        // Пытаемся найти существующего гостя
        if ($guestUuid) {

            $existingGuest = User::query()
                ->where('uuid', $guestUuid)
                ->where('role', RoleEnum::GUEST->value)
                ->first();

            // Если нашли, но это уже НЕ гость → создаём нового гостя
            if ($existingGuest && $existingGuest->role !== RoleEnum::GUEST->value) {
                $data = $this->createNewGuest();
                // Перегенерируем session
                $request->session()->regenerate();

                return $data;
            }

            if ($existingGuest) {

                Auth::guard('web')->login($existingGuest);

                // Перегенерируем session
                $request->session()->regenerate();

                return response()->json([
                    'user' => $existingGuest,
                    'restored' => true,
                    'session_id' => session()->getId(),
                ]);
            }
        }

        // Создаём нового гостя
        $uuid = (string)Str::uuid();

        $guest = User::query()->create([
            'role' => RoleEnum::GUEST->value,
            'uuid' => $uuid,
            'email' => $uuid . '@guest.local',
            'password' => Hash::make(Str::random(64)),
        ]);

        // Авторизация
        Auth::guard('web')->login($guest);

        // Обновляем session
        $request->session()->regenerate();

        return response()
            ->json([
                'user' => $guest,
                'created' => true,
                'session_id' => session()->getId(),
            ])
            ->cookie(
                'guest_uuid',
                $uuid,
                60 * 24 * 365,
                '/',
                null,
                app()->environment('production'),
                true,
                false,
                'lax'
            );
    }

    /**
     * Апгрейд гостя до полноценного пользователя
     */
    public function upgrade(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Не авторизован'
            ], 401);
        }

        $data = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',

            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone,' . $user->id
            ],

            'birth_date' => 'required|date',
            'municipality_id' => 'required|integer',
            'city' => 'required|string|max:255',

            'password' => 'required|string|min:6',

            'agree' => 'required|accepted'
        ]);

        $user->update([
            'name' => trim(
                $data['last_name'] . ' ' .
                $data['first_name'] . ' ' .
                ($data['middle_name'] ?? '')
            ),

            'phone' => $data['phone'],

            'personal_info' => [
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'birth_date' => $data['birth_date'],
                'municipality_id' => $data['municipality_id'],
                'city' => $data['city'],
                'agree_personal_data' => true,
            ],

            'password' => Hash::make($data['password']),

            'role' => RoleEnum::USER->value,
        ]);

        return response()->json([
            'user' => $user->fresh()
        ]);
    }

    /**
     * Авторизация пользователя
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::query()
            ->where(function ($query) use ($data) {
                $query
                    ->where('phone', $data['login'])
                    ->orWhere('email', $data['login']);
            })
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {

            return response()->json([
                'message' => 'Неверные данные'
            ], 422);
        }

        // Удаляем текущую сессию гостя
        Auth::logout();

        // Логиним пользователя
        Auth::login($user);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Текущий пользователь
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * Выход
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'success' => true
        ])->withoutCookie('guest_uuid');
    }
}
