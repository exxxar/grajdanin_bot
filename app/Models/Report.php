<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_type',
        'from_user_id',
        'to_user_id',
        'priority',
        'municipality_id',
        'received_at',
        'documents',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'from_user_id' => 'integer',
            'to_user_id' => 'integer',
            'priority' => 'integer',
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
        return $this->belongsTo(Users::class);
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(Users::class);
    }

    public function userIncomingReportResultReportEventRequest(): HasOne
    {
        return $this->hasOne(UserIncomingReportResultReportEventRequest::class);
    }
}
