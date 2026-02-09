<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Анкета волонтёра — {{ $lastName }} {{ $firstName }}</title>
    <style>
        @page { margin: 2cm; }
        body {
            font-family: DejaVu Sans, DejaVuSans, sans-serif;
            font-size: 12pt;
        }
        h1 {
            color: #2b6cb0;
            text-align: center;
            margin-bottom: 28px;
            font-size: 22px;
        }
        h2 {
            color: #2c5282;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin: 24px 0 12px;
            font-size: 16px;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .label {
            width: 240px;
            font-weight: 600;
            color: #4a5568;
        }
        .value {
            flex: 1;
        }
        .list {
            margin-left: 20px;
            padding-left: 20px;
        }
        .yes { color: #38a169; }
        .no  { color: #e53e3e; }
        .section { margin-bottom: 18px; }
    </style>
</head>
<body>

<h1>Анкета волонтёра</h1>

<h2>Личные данные</h2>
<div class="row"><span class="label">Фамилия:</span><span class="value">{{ $lastName }}</span></div>
<div class="row"><span class="label">Имя:</span><span class="value">{{ $firstName }}</span></div>
<div class="row"><span class="label">Отчество:</span><span class="value">{{ $middleName ?: '—' }}</span></div>
<div class="row"><span class="label">Дата рождения:</span><span class="value">{{ $birthDate }}</span></div>
<div class="row"><span class="label">Пол:</span><span class="value">{{ $gender_text }}</span></div>
<div class="row"><span class="label">Гражданство:</span><span class="value">{{ $citizenship ?: '—' }}</span></div>

<h2>Контакты</h2>
<div class="row"><span class="label">Телефон:</span><span class="value">{{ $phone }}</span></div>
<div class="row"><span class="label">Email:</span><span class="value">{{ $email }}</span></div>
<div class="row"><span class="label">Город:</span><span class="value">{{ $city }}</span></div>
<div class="row"><span class="label">Адрес:</span><span class="value">{{ $address ?: '—' }}</span></div>
<div class="row"><span class="label">Предпочтительный способ связи:</span><span class="value">{{ $preferredContact ?: '—' }}</span></div>

<h2>Мессенджеры и соцсети</h2>
<div class="row"><span class="label">Telegram:</span><span class="value">{{ $telegram ?: '—' }}</span></div>
<div class="row"><span class="label">WhatsApp:</span><span class="value">{{ $whatsapp ?: '—' }}</span></div>
<div class="row"><span class="label">VK / FB / Instagram:</span><span class="value">{{ $vk ?: '—' }}</span></div>
<div class="row"><span class="label">Личный сайт:</span><span class="value">{{ $website ?: '—' }}</span></div>

<h2>Профессиональная информация</h2>
<div class="row"><span class="label">Профессия:</span><span class="value">{{ $profession ?: '—' }}</span></div>
<div class="row"><span class="label">Навыки:</span>
    <span class="value">
            @if(!empty($skills)){{ implode(', ', $skills) }}@else—@endif
        </span>
</div>
<div class="row"><span class="label">Иностранные языки:</span><span class="value">{{ $languages ?: '—' }}</span></div>
<div class="row"><span class="label">Опыт волонтёрства:</span><span class="value">{{ $hasExperience_text }}</span></div>

@if($hasExperience)
    <div class="row"><span class="label">Стаж (лет):</span><span class="value">{{ $experience }}</span></div>
    <div class="section">
        <strong>Какие задачи выполняли:</strong><br>
        <div style="white-space: pre-wrap;">{{ $experienceDetails ?: '—' }}</div>
    </div>
@endif

<div class="row"><span class="label">Готовность к обучению:</span><span class="value">{{ $readyToLearn ?: '—' }}</span></div>

<h2>Готовность к участию</h2>
<div class="row"><span class="label">Часы в неделю:</span><span class="value">{{ $timePerWeek ?: '—' }}</span></div>
<div class="row"><span class="label">Формат участия:</span><span class="value">{{ $participationFormat ?: '—' }}</span></div>
<div class="row"><span class="label">Возможность выезжать:</span><span class="value">{{ $canTravel ?: '—' }}</span></div>
<div class="row"><span class="label">Работа в выходные:</span><span class="value">{{ $weekendWork ?: '—' }}</span></div>

<div class="row"><span class="label">Направления деятельности:</span>
    <span class="value">
            @if(!empty($directions)){{ implode(', ', $directions) }}@endif
        @if($otherDirection) {{ $otherDirection ? "($otherDirection)" : '' }}@endif
        @if(empty($directions) && !$otherDirection) — @endif
        </span>
</div>

<div class="row"><span class="label">Размер одежды:</span><span class="value">{{ $clothingSize ?: '—' }}</span></div>
<div class="row"><span class="label">Присылать задания:</span><span class="value {{ $sendTasks ? 'yes' : 'no' }}">{{ $sendTasks_text }}</span></div>

<h2>Мотивация и здоровье</h2>
<div class="section">
    <strong>Почему хотите стать волонтёром:</strong><br>
    <div style="white-space: pre-wrap;">{{ $motivation ?: '—' }}</div>
</div>

<div class="section">
    <strong>Ограничения по здоровью:</strong><br>
    @if($healthLimit === 'none')
        <span class="yes">Нет ограничений</span>
    @elseif($healthLimit === 'has')
        <span class="no">Есть ограничения:</span>
        @if(!empty($healthDetails))
            <ul class="list">
                @foreach($healthDetails as $detail)
                    <li>{{ $detail }}</li>
                @endforeach
            </ul>
        @else
            (указаны, но детали не заполнены)
        @endif
    @else
        —
    @endif
</div>

<div class="section">
    <strong>Дополнительные комментарии по здоровью:</strong><br>
    <div style="white-space: pre-wrap;">{{ $health ?: '—' }}</div>
</div>

<div class="section">
    <strong>Комментарий:</strong><br>
    <div style="white-space: pre-wrap;">{{ $comment ?: '—' }}</div>
</div>

<div style="margin-top: 40px; font-size: 11px; color: #718096; text-align: center;">
    Анкета создана: {{ now()->format('d.m.Y H:i') }}
</div>

</body>
</html>
