<?php

namespace App\Http\Controllers;

use App\Enums\ChatTypeEnum;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Получить сообщения по report_id
    public function index($reportId)
    {
        return Message::where('report_id', $reportId)
            ->orderBy('created_at')
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
         //   'report_id' => 'required|exists:reports,id',
            'chat_id' => 'nullable|exists:chats,id',
            'group' => 'nullable|string',
            'type' => 'nullable|in:user,admin,system',
            'text' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'string'
        ]);

        // 1. Если chat_id нет — создаём чат
        if (empty($data['chat_id'])) {
            $chat = Chat::firstOrCreate([
                'title'=>'Новый чат',
                'report_id' => $data['report_id'] ?? null,
                'type' => ChatTypeEnum::DEFAULT->value,
            ]);

            $data['chat_id'] = $chat->id;
        }

        // 2. Создаём сообщение
        $data['user_id'] = $request->user()->id ?? null;

        $message = Message::create([
            'user_id'=>$data['user_id']  ?? null,
            'report_id'=>$data['report_id']  ?? null,
            'chat_id'=>$data['chat_id']  ?? null,
            'text'=>$data['text']  ?? null,
            'attachments'=>$data['attachments']  ?? null,
        ]);

        return response()->json([
            'message' => $message,
            'chat_id' => $data['chat_id']
        ], 201);
    }

    // Получить сообщения чата
    public function chatMessages($chatId)
    {
        return Message::where('chat_id', $chatId)
            ->orderBy('created_at')
            ->get();
    }

    public function userChats(Request $request)
    {
        $userId = $request->user()->id;

        return Chat::query()
            ->where(function ($q) use ($userId) {
                $q->whereHas('messages', fn ($m) => $m->where('user_id', $userId))
                    ->orWhereHas('report', fn ($r) => $r->where('from_user_id', $userId));
            })
            ->with(['report.incomingReport'])
            ->latest()
            ->get();
    }


    // Показать одно сообщение
    public function show(Message $message)
    {
        return $message;
    }

    // Удалить сообщение (только админ)
    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json(['status' => 'deleted']);
    }
}
