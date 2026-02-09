<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчет</title>
    <style>
        @page { margin: 2cm; }
        body {
            font-family: DejaVu Sans, DejaVuSans, sans-serif;
            font-size: 12pt;
        }
        h1 { text-align: center; }
        .section { margin-bottom: 20px; }
        img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
<h1>Отчет по проблеме</h1>

<div class="section">
    <h2>Описание проблемы</h2>
    <p>{{ $problem_description ?? 'Нет описания' }}</p>
</div>

<div class="section">
    <h2>Комментарий</h2>
    <p>{{ $comment ?? 'Нет комментария' }}</p>
</div>

<div class="section">
    <h2>Телефон</h2>
    <p>{{ $phone ?? 'Нет телефона' }}</p>
</div>

<div class="section">
    <h2>Получено от</h2>
    <p>{{ $received_from ?? 'Не указано' }}</p>
</div>

<div class="section">
    <h2>Дата получения</h2>
    <p>{{ $received_at ?? 'Не указано' }}</p>
</div>

<div class="section">
    <h2>Муниципалитет ID</h2>
    <p>{{ $municipality_id ?? 'Не указано' }}</p>
</div>

<div class="section">
    <h2>Тип</h2>
    <p>{{ $type ?? 'Не указано' }}</p>
</div>

<div class="section">
    <h2>Форматы помощи</h2>
    <ul>
        @foreach($help_formats ?? [] as $format)
            <li>{{ $format }}</li>
        @endforeach
    </ul>
</div>

<div class="section">
    <h2>Проблемы</h2>
    @foreach($problems ?? [] as $key => $problemGroup)
        @if(is_array($problemGroup))
            <h3>Группа {{ $key }}</h3>
            <ul>
                @foreach($problemGroup as $problem)
                    <li>{{ $problem }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach
</div>

<div class="section">
    <h2>Трудности</h2>
    @foreach($difficulties ?? [] as $key => $difficultyGroup)
        @if(is_array($difficultyGroup))
            <h3>Группа {{ $key }}</h3>
            <ul>
                @foreach($difficultyGroup as $difficulty)
                    <li>{{ $difficulty }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach
</div>

<div class="section">
    <h2>Решения</h2>
    @foreach($solutions ?? [] as $key => $solutionGroup)
        @if(is_array($solutionGroup))
            <h3>Группа {{ $key }}</h3>
            <ul>
                @foreach($solutionGroup as $solution)
                    <li>{{ $solution }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach
</div>

<div class="section">
    <h2>Документы</h2>
    @foreach($documentPaths ?? [] as $path)
        <img src="{{ $path }}" alt="Документ">
    @endforeach
</div>

<!-- Аудиофайлы игнорируем, так как массив пустой, но если нужно - добавьте обработку -->

</body>
</html>
