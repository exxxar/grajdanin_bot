<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function vkRedirect()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function vkCallback()
    {
        $vk = Socialite::driver('vkontakte')->user();

        return $this->handleOAuthUser(
            provider: 'vk',
            providerId: $vk->id,
            email: $vk->email,
            name: $vk->name
        );
    }

    public function yandexRedirect()
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function yandexCallback()
    {
        $ya = Socialite::driver('yandex')->user();

        return $this->handleOAuthUser(
            provider: 'yandex',
            providerId: $ya->id,
            email: $ya->email,
            name: $ya->name
        );
    }

    private function handleOAuthUser($provider, $providerId, $email, $name)
    {
        // 1. Ищем пользователя
        $user = User::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        // 2. Если нет — апгрейдим гостя
        if (!$user) {
            $guest = auth()->user(); // текущий гость

            $guest->update([
                'provider' => $provider,
                'provider_id' => $providerId,
                'email' => $email,
                'name' => $name,
                'role' => 'user'
            ]);

            $user = $guest;
        }

        // 3. Создаём токен
        $token = $user->createToken('auth')->plainTextToken;

        // 4. Возвращаем в Vue
        return redirect("https://yourapp.com/oauth-success?token=$token");
    }
}
