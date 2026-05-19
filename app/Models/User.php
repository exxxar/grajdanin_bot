<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'uuid',
        'email',
        'password',
        'personal_info',
        "role",
        "email_verified_at",
        "password",
        "blocked_at",
        "yandex_auth_token",
        "blocked_message",
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'personal_info' => 'array',
        'password' => 'hashed',
    ];


    public function getRoleName(): string
    {
        $roles = [
            'Гость',
            'Пользователь',
            'Волонтер',
            'Должностное лицо',
            'Администратор',
            'Суперадмин',
        ];

        return $roles[$this->role] ?? 'Неизвестная роль';
    }

    public function toTelegramText(): string
    {
        $fields = [
            'Имя' => $this->name,
            'Email' => $this->email,
            'Телефон' => $this->phone ?? '-',
            'Персональная информация' => $this->personal_info ?? '-',
            'Роль' => $this->getRoleName(),
            'Email подтверждён' => $this->email_verified_at,
            'Дата заполнения профиля' => $this->registration_at ?? 'не заполнен',
            'Дата блокировки' => $this->blocked_at ?? 'не заблокирован',
            'Сообщение блокировки' => $this->blocked_message,
            'Создан' => $this->created_at,
            'Обновлён' => $this->updated_at,
        ];

        $text = "";
        foreach ($fields as $label => $value) {
            if (!empty($value)) {
                $text .= "{$label}: {$value}\n";
            }
        }

        return trim($text);
    }

}
