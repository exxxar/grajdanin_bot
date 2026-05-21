<?php

namespace App\Services;

use App\Models\IncomingReport;
use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CrmNotifier
{
    public function notifyIncomingReport(Report $report, IncomingReport $incoming, string $reportUrl): void
    {
        $webhook = config('services.crm.webhook_url');

        if (!$webhook) {
            return;
        }

        try {
            Http::timeout(10)->post($webhook, [
                'event' => 'incoming_report.created',
                'report_id' => $report->id,
                'incoming_report_id' => $incoming->id,
                'url' => $reportUrl,
                'received_from' => $incoming->received_from,
                'phone' => $report->phone,
                'problem_description' => $incoming->problem_description,
                'municipality_id' => $report->municipality_id,
                'created_at' => $incoming->created_at?->toIso8601String(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('CRM webhook failed', [
                'report_id' => $report->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
