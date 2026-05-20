<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomingReportStoreRequest;
use App\Http\Resources\IncomingReportResource;
use App\Models\IncomingReport;
use App\Services\IncomingReportService;
use Illuminate\Http\Request;

class IncomingReportsController extends Controller
{
    public function __construct(
        private readonly IncomingReportService $incomingReportService,
    ) {
    }

    public function index()
    {
        return IncomingReportResource::collection(
            IncomingReport::with('report')->latest()->get()
        );
    }

    public function mine(Request $request)
    {
        $items = IncomingReport::query()
            ->with('report')
            ->whereHas('report', fn ($q) => $q->where('from_user_id', $request->user()->id))
            ->latest()
            ->get();

        return IncomingReportResource::collection($items);
    }

    public function show($id)
    {
        $incoming = IncomingReport::with('report')->findOrFail($id);

        return new IncomingReportResource($incoming);
    }

    public function store(IncomingReportStoreRequest $request)
    {
        $result = $this->incomingReportService->createFromRequest(
            $request,
            $request->user()
        );

        return (new IncomingReportResource($result['incoming']))
            ->additional([
                'chat_id' => $result['chat_id'],
                'report_url' => $result['report_url'],
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, $id)
    {
        $incoming = IncomingReport::findOrFail($id);

        $validated = $request->validate([
            'report_id' => ['required', 'integer', 'exists:reports,id'],
            'received_from' => ['nullable', 'string', 'max:255'],
            'problem_description' => ['required', 'string', 'min:10', 'max:5000'],
            'help_formats' => ['nullable', 'array'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $incoming->update($validated);

        return new IncomingReportResource($incoming->load('report'));
    }

    public function destroy($id)
    {
        $incoming = IncomingReport::with('report')->findOrFail($id);
        $incoming->report?->delete();
        $incoming->delete();

        return response()->json(['message' => 'Входящий отчёт удалён']);
    }
}
