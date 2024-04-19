<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\WeatherReport;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\WeatherController;


// To run scheduler in local environment run the following command  'php artisan schedule:run'
// To run scheduler in production environment add the following cron job to the server  '* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1' 

// Schedule the task to send the email to the user at the preferred email time if the current time is the same as the preferred email time
Schedule::call(function (WeatherController $weatherController) {
    foreach (User::all() as $user) {
        if (!$user->preferred_email_time  || !$user->city || !$user->timezone) {
            Log::info('User ' . $user->id . ' does not have all the required fields set');
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
            $weatherData = $weatherController->getWeatherData($city);
            if (!$weatherData) {
                continue;
            }
            $clothingSuggestions = $weatherController->getSuggestions($weatherData['main']['temp'], $weatherData['weather'][0]['description']);
            if (!$clothingSuggestions) {
                continue;
            }
            $weatherData['suggestions'] = $clothingSuggestions;
            Mail::to($recipient)->send(new WeatherReport($weatherData));
            Log::info('Email sent');
        } else {
            Log::info('No email to send');
        }
    }
});
