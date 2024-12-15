<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/weather/today', [WeatherController::class, 'getTodayWeather'])->name('weather.today');
Route::get('/weather/weekly', [WeatherController::class, 'getWeeklyWeather'])->name('weather.weekly');



