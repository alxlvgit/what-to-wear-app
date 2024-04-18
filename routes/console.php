<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\WeatherReport;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

Schedule::call(function () {
    foreach (User::all() as $user) {
        if (!$user->preferred_email_time  || !$user->city || !$user->timezone) {
            continue;
        }
        $preferredEmailTime = Carbon::createFromFormat('H:i', $user->preferred_email_time);
        // Get the current time
        $currentDateTime = Carbon::now($user->timezone);
        // Set the current time to the same date as the preferred email time
        $currentDateTime->setTime($preferredEmailTime->hour, $preferredEmailTime->minute);
        if ($currentDateTime->equalTo(Carbon::now($user->timezone)->startOfMinute())) {
            $recipient = $user->email;
            $city = $user->city;
            $response = Http::get('http://api.openweathermap.org/geo/1.0/direct?q=' . $city . '&limit=5&appid=' . env('OPENWEATHER_API_KEY'));
            if ($response->failed()) {
                Log::info('Failed to fetch weather data. Please try again.');
                return;
            }

            $data = $response->json();

            if (empty($data)) {
                Log::info('Failed to fetch weather data. Please try again.');
                return;
            }

            $lat = $data[0]['lat'];
            $lon = $data[0]['lon'];

            $weatherResponse = Http::get('http://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lon . '&appid=' . env('OPENWEATHER_API_KEY') . '&units=metric');

            if ($weatherResponse->successful()) {
                $weatherData = $weatherResponse->json();
            } else {
                Log::info('Failed to fetch weather data. Please try again.');
                return;
            }
            Mail::to($recipient)->send(new WeatherReport($weatherData));
            Log::info('Email sent');
        } else {
            Log::info('No email to send');
        }
    }
});
