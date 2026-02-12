<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send newsletter editions that are scheduled for now or earlier.
// Runs every 15 minutes so e.g. a 1:30pm schedule sends by ~1:45pm (requires cron: * * * * * php artisan schedule:run).
Schedule::command('newsletter:send-scheduled')->everyFifteenMinutes();
