<?php

namespace App\Services;

class ReportUrlBuilder
{
    public static function forReport(int $reportId): string
    {
        $base = rtrim(config('app.url'), '/');

        return "{$base}/app/chat?report={$reportId}";
    }
}
