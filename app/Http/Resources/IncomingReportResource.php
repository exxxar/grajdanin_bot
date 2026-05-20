<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomingReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'report_id' => $this->report_id,
            'received_from' => $this->received_from,
            'problem_description' => $this->problem_description,
            'help_formats' => $this->help_formats,
            'comment' => $this->comment,
            'problems' => $this->problems,
            'solutions' => $this->solutions,
            'difficulties' => $this->difficulties,
            'audio_files' => $this->audio_files,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'report' => ReportResource::make($this->whenLoaded('report')),
        ];
    }
}
