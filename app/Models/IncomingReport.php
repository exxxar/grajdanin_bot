<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'received_from',
        'problem_description',
        'help_formats',
        'comment',
        'problems',
        'solutions',
        'difficulties',
        'audio_files',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'report_id' => 'integer',
            'help_formats' => 'array',
            'problems' => 'array',
            'solutions' => 'array',
            'difficulties' => 'array',
            'audio_files' => 'array',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
