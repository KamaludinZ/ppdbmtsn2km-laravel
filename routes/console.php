<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule materialized view refresh
// Refresh every 5 minutes during business hours (7 AM - 6 PM)
Schedule::command('dashboard:refresh-views --concurrent')
    ->everyFiveMinutes()
    ->between('07:00', '18:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/materialized-views.log'));

// Refresh every 15 minutes outside business hours
Schedule::command('dashboard:refresh-views --concurrent')
    ->everyFifteenMinutes()
    ->unlessBetween('07:00', '18:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/materialized-views.log'));
