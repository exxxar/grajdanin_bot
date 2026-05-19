<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['report_id', 'type','title'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
