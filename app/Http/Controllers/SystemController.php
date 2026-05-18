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



    public function homePage(Request $request, $any = null)
    {
        Inertia::setRootView("mobile");
        return Inertia::render('Main');
    }

}
