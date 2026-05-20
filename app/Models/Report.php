<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'priority',
        'from_user_id',
        'to_user_id',
        'municipality_id',
        'received_at',
        'phone',
        'documents',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'type' => 'integer',
            'priority' => 'integer',
            'from_user_id' => 'integer',
            'to_user_id' => 'integer',
            'municipality_id' => 'integer',
            'documents' => 'array',
        ];
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function incomingReport(): HasOne
    {
        return $this->hasOne(IncomingReport::class);
    }

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }
}
