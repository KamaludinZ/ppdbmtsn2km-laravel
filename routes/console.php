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

// WhatsApp Queue Processing
// Process queue every minute during working hours (6 AM - 6 PM)
// The service will handle the 5-10 minute intervals between messages
Schedule::command('whatsapp:process-queue')
    ->everyMinute()
    ->between('06:00', '18:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/whatsapp-queue.log'));

// Re-schedule overdue messages after working hours
// Run at 18:05 (after working hours end) and 00:05 (midnight)
Schedule::call(function () {
    $queueService = app(\App\Services\WhatsAppQueueService::class);
    $count = $queueService->rescheduleOverdueMessages();
    \Illuminate\Support\Facades\Log::info("Scheduled reschedule overdue messages: {$count} messages rescheduled");
})
    ->dailyAt('18:05')
    ->name('reschedule-overdue-whatsapp-evening')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/whatsapp-queue.log'));

Schedule::call(function () {
    $queueService = app(\App\Services\WhatsAppQueueService::class);
    $count = $queueService->rescheduleOverdueMessages();
    \Illuminate\Support\Facades\Log::info("Scheduled reschedule overdue messages: {$count} messages rescheduled");
})
    ->dailyAt('00:05')
    ->name('reschedule-overdue-whatsapp-midnight')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/whatsapp-queue.log'));
