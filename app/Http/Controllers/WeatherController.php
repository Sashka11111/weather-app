<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{public function getTodayWeather(Request $request)
{
    $city = $request->query('city');
    $country = $request->query('country');
    $apiKey = env('OPENWEATHER_API_KEY');

    // Перевіряємо, який запит надійшов
    $forecastType = $request->query('forecast');

    if ($forecastType === 'weekly') {
        return $this->getWeeklyWeather($request);  // Перехід до методу прогнозу на тиждень
    }

    // Формуємо запит для погоди на сьогодні
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city},{$country}&appid={$apiKey}&units=metric&lang=uk";
    $response = Http::get($url);

    if ($response->failed()) {
        return view('welcome', ['error' => 'Місто не знайдено']);
    }

    $data = $response->json();
    return view('welcome', ['weather' => $data]);
}

    public function getWeeklyWeather(Request $request)
    {
        $city = $request->query('city');
        $country = $request->query('country');
        $apiKey = env('OPENWEATHER_API_KEY');

        // Формування запиту для отримання прогнозу на кілька днів
        $url = "https://api.openweathermap.org/data/2.5/forecast?q={$city},{$country}&appid={$apiKey}&units=metric&lang=uk";
        $response = Http::get($url);

        if ($response->failed()) {
            return view('welcome', ['error' => 'Місто не знайдено']);
        }

        $data = $response->json();

        // Групуємо прогноз по днях
        $forecast = [];

        foreach ($data['list'] as $hourlyData) {
            // Витягуємо дату (тільки день, без часу)
            $date = date('Y-m-d', $hourlyData['dt']);

            // Якщо для цього дня ще немає даних в масиві, додаємо новий елемент
            if (!isset($forecast[$date])) {
                $forecast[$date] = [
                    'date' => $date,
                    'temp' => 0,
                    'count' => 0,
                    'description' => '',
                    'icon' => ''
                ];
            }

            // Додаємо температуру та опис для цього дня
            $forecast[$date]['temp'] += $hourlyData['main']['temp'];
            $forecast[$date]['count']++;

            // Оновлюємо опис погоди та іконку
            if ($forecast[$date]['description'] === '') {
                $forecast[$date]['description'] = $hourlyData['weather'][0]['description'];
                $forecast[$date]['icon'] = $hourlyData['weather'][0]['icon'];
            }
        }

        // Середня температура для кожного дня
        foreach ($forecast as $date => &$day) {
            $day['temp'] = round($day['temp'] / $day['count'], 1); // середня температура
        }

        // Повертаємо оброблені дані
        return view('welcome', ['forecast' => $forecast]);
    }

}
