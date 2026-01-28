<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportsController extends Controller
{


    public function store(Request $request)
    {
        // -----------------------------
        // 1. Валидация
        // -----------------------------
        $validated = $request->validate([
            'type' => ['required', 'integer'],
            'phone' => ['nullable', 'string'],
            'from_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'to_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'municipality_id' => ['required', 'integer', 'exists:municipalities,id'],
            'received_at' => ['required', 'date'],
            'received_from' => ['nullable', 'string'],
            'problem_description' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],

            // массивы
            'help_formats' => ['array'],
            'help_formats.*' => ['nullable', 'integer', 'exists:help_formats,id'],

            'problems' => ['array'],
            'problems.*' => ['integer', 'exists:issue_categories,id'],

            'solutions' => ['array'],
            'solutions.*' => ['integer', 'exists:issue_categories,id'],

            'difficulties' => ['array'],
            'difficulties.*' => ['integer', 'exists:issue_categories,id'],

            // файлы
            'documents' => ['array'],
            'documents.*' => ['file', 'max:20480'],

            'audio_files' => ['array'],
            'audio_files.*' => ['file', 'mimetypes:audio/mpeg,audio/wav,audio/mp4', 'max:20480'],
        ]);

        // -----------------------------
        // 2. Создание основного отчёта
        // -----------------------------
        $report = Report::create([
            'type' => $validated['type'],
            'phone' => $validated['phone'] ?? null,
            'from_user_id' => $validated['from_user_id'] ?? null,
            'to_user_id' => $validated['to_user_id'] ?? null,
            'municipality_id' => $validated['municipality_id'],
            'received_at' => $validated['received_at'],
            'received_from' => $validated['received_from'] ?? null,
            'problem_description' => $validated['problem_description'] ?? null,
            'comment' => $validated['comment'] ?? null,
        ]);

        // -----------------------------
        // 3. Сохранение справочников
        // -----------------------------
        if (!empty($validated['help_formats'])) {
            $report->helpFormats()->sync($validated['help_formats']);
        }

        if (!empty($validated['problems'])) {
            $report->problems()->sync($validated['problems']);
        }

        if (!empty($validated['solutions'])) {
            $report->solutions()->sync($validated['solutions']);
        }

        if (!empty($validated['difficulties'])) {
            $report->difficulties()->sync($validated['difficulties']);
        }

        // -----------------------------
        // 4. Загрузка документов
        // -----------------------------
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('reports/documents', 'public');
                $report->documents()->create([
                    'path' => $path
                ]);
            }
        }

        // -----------------------------
        // 5. Загрузка аудио
        // -----------------------------
        if ($request->hasFile('audio_files')) {
            foreach ($request->file('audio_files') as $file) {
                $path = $file->store('reports/audio', 'public');
                $report->audioFiles()->create([
                    'path' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Отчёт успешно создан',
            'report' => $report->load([
                'helpFormats',
                'problems',
                'solutions',
                'difficulties',
                'documents',
                'audioFiles'
            ])
        ]);
    }

public function index()
    {
        return response()->json(Report::all());
    }

    public function show($id)
    {
        return response()->json(Report::findOrFail($id));
    }



    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $validated = $request->validate([
            'type'            => 'nullable|integer',
            'from_user_id'    => 'nullable|integer',
            'to_user_id'      => 'required|integer',
            'municipality_id' => 'required|integer',
            'received_at'     => 'required|string',
            'documents'       => 'nullable|array',
        ]);

        $report->update($validated);
        return response()->json($report);
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Отчёт удалён']);
    }
}
