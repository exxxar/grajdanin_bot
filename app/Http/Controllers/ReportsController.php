<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return ReportResource::collection(Report::latest()->get());
    }

    public function show($id)
    {
        return new ReportResource(Report::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'integer'],
            'from_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'to_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'municipality_id' => ['required', 'integer', 'exists:municipalities,id'],
            'received_at' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:32'],
            'documents' => ['nullable', 'array'],
        ]);

        $report = Report::create($validated);

        return (new ReportResource($report))
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $validated = $request->validate([
            'type' => ['nullable', 'integer'],
            'from_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'to_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'municipality_id' => ['required', 'integer', 'exists:municipalities,id'],
            'received_at' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:32'],
            'documents' => ['nullable', 'array'],
        ]);

        $report->update($validated);

        return new ReportResource($report);
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Отчёт удалён']);
    }
}
