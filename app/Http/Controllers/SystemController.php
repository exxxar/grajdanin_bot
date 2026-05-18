<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class SystemController extends Controller
{
    public function getSelf(Request $request)
    {
        if (env("APP_DEBUG")) {
            $user = User::query()->first();
            $user->role = RoleEnum::SUPERADMIN->value;
            $user->base_role = RoleEnum::SUPERADMIN->value;
        } else {
            $user = User::query()
                ->find($request->botUser->id);
            $user->base_role = $user->role;
            Log::info("ENV DEBUG FALSE". print_r($user->toArray(),true));
        }


        return response()->json($user);
    }

    public function registerWebhooks(Request $request)
    {
        return response()->json(BotManager::bot()->setWebhook());
    }

    public function handler(Request $request)
    {
        BotManager::bot()->handler();

        return response()->json([
            "message" => "Ok"
        ]);
    }

    public function uploadAnyKindOfMedia(...$data)
    {
        $caption = $data[2] ?? null;
        $doc = $data[3] ?? null;
        $type = $data[4] ?? "document";

        $botUser = BotManager::bot()->currentBotUser();

        if (!$botUser->is_admin && !$botUser->is_manager) {
            BotManager::bot()
                ->sendMessage(
                    $botUser->telegram_chat_id,
                    "Данная опция доступна только персоналу бота!");
            return;
        }

        $docToSend = $doc->file_id ?? null;


        BotManager::bot()
            ->sendMessage(
                $botUser->telegram_chat_id,
                "Медиа файл загружен!");

    }

    public function getMyId(...$data)
    {
        $message = "Ваш чат id: <pre><code>" . ($data[0]->chat->id ?? 'не указан') . "</code></pre>\nИдентификатор топика: " . ($data[0]->message_thread_id ?? 'Не указан');

        BotManager::bot()
            ->reply($message);
    }

    public function aboutCommand(...$data)
    {
        BotManager::bot()
            ->replyPhoto("Хочешь такой же бот для своего бизнеса? ",
                InputFile::create(public_path() . "/images/cashman.jpg"),
                [
                    [
                        [
                            "text" => "🔥Перейти в нашего бота для заявок",
                            "url" => "https://t.me/cashman_dn_bot"
                        ]
                    ],
                    [
                        [
                            "text" => "\xF0\x9F\x8D\x80Написать в тех. поддержку",
                            "url" => "https://t.me/EgorShipilov"
                        ],
                    ],

                ]
            );
    }

    public function helpCommand(...$data) {
        BotManager::bot()->reply("Как пользоваться ботом");
    }


    public function homePage(Request $request)
    {
        Inertia::setRootView("mobile");
        return Inertia::render('Main');
    }

    public function startCommand()
    {

        $keyboard = [
            [
                ["text" => "💎Войти в систему", "web_app" => [
                    "url" => env("APP_URL") . "/bot#/"]
                ],
            ],
        ];
        \App\Facades\BotManager::bot()
            ->replyInlineKeyboard("Система управления заявками", $keyboard);
    }
}
