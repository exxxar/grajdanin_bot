<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">

    <title>Информация о мероприятии — {{ $event_date ?? '—' }}</title>
    <style>
        @page { margin: 2cm; }
        body {
            font-family: DejaVu Sans, DejaVuSans, sans-serif;
            font-size: 12pt;
        }
        h1 {
            text-align: center;
            color: #1a3c6d;
            margin-bottom: 30px;
        }
        h2 {
            color: #2c5282;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-top: 30px;
        }
        .field {
            margin-bottom: 18px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 220px;
            color: #4a5568;
        }
        .value {
            color: #2d3748;
        }
        .phone {
            color: #3182ce;
        }




    </style>
</head>
<body>

<h1>Информация о мероприятии</h1>

<div class="field">
    <span class="label">Дата мероприятия:</span>
    <span class="value">{{ $event_date ?? '—' }}</span>
</div>

<div class="field">
    <span class="label">Муниципалитет ID:</span>
    <span class="value">{{ $municipality_id ?? '—' }}</span>
</div>

<div class="field">
    <span class="label">Количество участников:</span>
    <span class="value">{{ number_format($participants_count ?? 0, 0, '', ' ') }}</span>
</div>

<h2>Описание</h2>
<div class="field">
    <p style="white-space: pre-wrap;">{{ $description ?? 'Описание отсутствует' }}</p>
</div>

<h2>Целевая аудитория</h2>
<div class="field">
    <p>{{ $target_audience ?? 'Не указана' }}</p>
</div>

<h2>Дополнительная информация</h2>
<div class="field">
    <span class="label">Комментарий:</span>
    <span class="value">{{ $comment ?? '—' }}</span>
</div>

<div class="field">
    <span class="label">Контактный телефон:</span>
    <span class="value phone">{{ $phone ?? '—' }}</span>
</div>

<div class="field">
    <span class="label">Получено от:</span>
    <span class="value">{{ $received_from ?? '—' }}</span>
</div>

<h2>Форматы помощи / поддержки</h2>
<div class="field">
    @if(!empty($help_formats))
        <ul style="margin-left: 20px; padding-left: 20px;">
            @foreach($help_formats as $format)
                <li>Формат {{ $format }}</li>
            @endforeach
        </ul>
    @else
        <p>Не указаны</p>
    @endif
</div>

</body>
</html>
