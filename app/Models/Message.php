<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_id',
        'chat_id',
        'group',
        'type',
        'text',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
