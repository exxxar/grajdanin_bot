<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateIncomingPdf(Request $request)
    {
        // Валидация входных данных (опционально, но рекомендуется)
        $validated = $request->validate([
            'audio_files' => 'array',
            'comment' => 'string|nullable',
            'difficulties' => 'array',
            'documents' => 'array',
            'documents.*' => 'file|image|mimes:jpg,jpeg,png|max:2048', // Пример валидации файлов
            'from_user_id' => 'string|nullable',
            'help_formats' => 'array',
            'municipality_id' => 'integer',
            'phone' => 'string|nullable',
            'problem_description' => 'string|nullable',
            'problems' => 'array',
            'received_at' => 'string|date_format:Y-m-d',
            'received_from' => 'string|nullable',
            'solutions' => 'array',
            'to_user_id' => 'string|nullable',
            'type' => 'integer',
        ]);

        // Обработка файлов (документов)
        $documentPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                // Сохраняем файлы временно в storage (или в public для доступа)
                $path = $file->store('temp_documents', 'public');
                $documentPaths[] = storage_path('app/public/' . $path); // Полный путь для dompdf
            }
        }

        // Подготовка данных для вида
        $data = $validated;
        $data['documentPaths'] = $documentPaths;

        // Генерация PDF из Blade-вида
        $pdf = Pdf::loadView('pdf.report', $data);

        // Скачивание или поток (выберите нужное)
        $content = $pdf->output();//'event_' . $validated['event_date'] . '.pdf');
        // Или: return $pdf->stream('event-report.pdf');

        \App\Facades\BotMethods::bot()
            ->sendDocument(env("TELEGRAM_ADMIN_CHANNEL"),"Проблема",
                \Telegram\Bot\FileUpload\InputFile::createFromContents($content,"проблема.pdf"));

        return response()->noContent();
    }

    public function generateEventPdf(Request $request)
    {
        $validated = $request->validate([
            'comment'            => 'nullable|string|max:1000',
            'description'        => 'nullable|string|max:5000',
            'event_date'         => 'required|date_format:Y-m-d',
            'help_formats'       => 'nullable|array',
            'help_formats.*'     => 'integer',
            'municipality_id'    => 'required|integer',
            'participants_count' => 'required|integer|min:0',
            'phone'              => 'nullable|string|max:20',
            'received_from'      => 'nullable|string|max:255',
            'target_audience'    => 'nullable|string|max:1000',
        ]);

        // Подготовка данных для шаблона
        $data = $validated;

        // Генерация PDF
        $pdf = Pdf::loadView('pdf.event-report', $data)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);


        // Скачивание или поток (выберите нужное)
         $content = $pdf->output();//'event_' . $validated['event_date'] . '.pdf');
        // Или: return $pdf->stream('event-report.pdf');

        \App\Facades\BotMethods::bot()
            ->sendDocument(env("TELEGRAM_ADMIN_CHANNEL"),"Мероприятие",
                \Telegram\Bot\FileUpload\InputFile::createFromContents($content,"проведение мероприятия.pdf"));

        return response()->noContent();
    }

    public function generateVolunteerPdf(Request $request)
    {
        $validated = $request->validate([
            'lastName'           => 'required|string|max:100',
            'firstName'          => 'required|string|max:100',
            'middleName'         => 'nullable|string|max:100',
            'birthDate'          => 'required|date',
            'gender'             => 'nullable|in:0,1',
            'citizenship'        => 'nullable|string|max:100',
            'phone'              => 'required|string|max:30',
            'email'              => 'required|email|max:150',
            'city'               => 'required|string|max:150',
            'address'            => 'nullable|string|max:255',
            'preferredContact'   => 'nullable|string|in:Телефон,Мессенджер,Email',
            'telegram'           => 'nullable|string|max:100',
            'whatsapp'           => 'nullable|string|max:100',
            'vk'                 => 'nullable|string|max:150',
            'website'            => 'nullable|url|max:255',
            'profession'         => 'nullable|string|max:150',
            'skills'             => 'nullable|array',
            'languages'          => 'nullable|string|max:255',
            'hasExperience'      => 'nullable|boolean',
            'experience'         => 'nullable|integer|min:0|max:50',
            'experienceDetails'  => 'nullable|string|max:2000',
            'readyToLearn'       => 'nullable|string|in:Да,Нет',
            'timePerWeek'        => 'nullable|string|max:50',
            'participationFormat'=> 'nullable|string|in:Онлайн,Офлайн,Смешанный',
            'canTravel'          => 'nullable|string|in:Да,Нет',
            'weekendWork'        => 'nullable|string|in:Да,Нет',
            'directions'         => 'nullable|array',
            'otherDirection'     => 'nullable|string|max:200',
            'clothingSize'       => 'nullable|string|in:XS,S,M,L,XL,XXL',
            'sendTasks'          => 'nullable|boolean',
            'motivation'         => 'nullable|string|max:3000',
            'health'             => 'nullable|string|max:1000',
            'healthLimit'        => 'nullable|in:none,has',
            'healthDetails'      => 'nullable|array',
            'comment'            => 'nullable|string|max:2000',
            'agreePersonal'      => 'required|accepted',
            'agreeNotifications' => 'nullable|boolean',
            'agreeEvents'        => 'nullable|boolean',
        ]);

        $data = $validated;

        // Подготовка человеко-читаемых значений (опционально)
        $data['gender_text'] = match ($data['gender'] ?? null) {
            '1' => 'Мужской',
            '0' => 'Женский',
            default => '—',
        };

        $data['hasExperience_text'] = $data['hasExperience'] ? 'Да' : ($data['hasExperience'] === false ? 'Нет' : '—');
        $data['sendTasks_text']     = $data['sendTasks'] ? 'Да' : 'Нет';

        $pdf = Pdf::loadView('pdf.volunteer-profile', $data)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        // Можно добавить имя файла с ФИО
        $filename = str_replace(' ', '_', trim("{$data['lastName']}_{$data['firstName']}")) . '_volunteer.pdf';

        // Скачивание или поток (выберите нужное)
        $content = $pdf->output();//'event_' . $validated['event_date'] . '.pdf');
        // Или: return $pdf->stream('event-report.pdf');

        \App\Facades\BotMethods::bot()
            ->sendDocument(env("TELEGRAM_ADMIN_CHANNEL"),"Волонтер",
                \Telegram\Bot\FileUpload\InputFile::createFromContents($content,"заявка на волонтера.pdf"));

        return response()->noContent();
    }
}
