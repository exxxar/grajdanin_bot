<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'priority' => $this->priority,
            'from_user_id' => $this->from_user_id,
            'to_user_id' => $this->to_user_id,
            'municipality_id' => $this->municipality_id,
            'received_at' => $this->received_at,
            'phone' => $this->phone,
            'documents' => $this->documents,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
