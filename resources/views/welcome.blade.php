<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Прогноз погоди</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1 class="mt-5">Прогноз погоди</h1>

    <!-- Форма для введення міста та вибору країни -->
    <form id="weather-form" method="GET" action="{{ route('weather.today') }}">
        <div class="mb-3">
            <label for="city" class="form-label">Введіть місто:</label>
            <input type="text" id="city" name="city" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="country" class="form-label">Виберіть країну:</label>
            <select id="country" name="country" class="form-control" required>
                <option value="UA">Україна</option>
                <option value="US">США</option>
                <option value="GB">Великобританія</option>
                <option value="FR">Франція</option>
                <option value="DE">Німеччина</option>
            </select>
        </div>
        <button type="submit" name="forecast" value="today" class="btn btn-primary">Отримати прогноз на сьогодні</button>
        <button type="submit" name="forecast" value="weekly" class="btn btn-secondary">Прогноз на тиждень</button>
    </form>

    <!-- Виведення результату погоди на сьогодні -->
    @if (isset($weather))
        <div class="mt-5">
            <h2>Погода в {{ $weather['name'] }}</h2>
            <p>Температура: {{ $weather['main']['temp'] }}°C</p>
            <p>Опис: {{ $weather['weather'][0]['description'] }}</p>
            <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="Іконка погоди">
        </div>
    @endif

    <!-- Якщо є помилка -->
    @if (isset($error))
        <p style="color: red;" class="mt-3">{{ $error }}</p>
    @endif

    <!-- Виведення прогнозу на тиждень -->
    @if (isset($forecast))
        <div class="mt-5">
            <h3>Прогноз на тиждень</h3>
            @foreach ($forecast as $day)
                <div class="mb-3">
                    <h4>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $day['date'])->format('d.m.Y') }}</h4>
                    <p>Температура: {{ $day['temp'] }}°C</p>
                    <p>Опис: {{ $day['description'] }}</p>
                    <img src="https://openweathermap.org/img/wn/{{ $day['icon'] }}@2x.png" alt="Іконка погоди">
                </div>
            @endforeach
        </div>
    @endif
</div>
</body>
</html>
