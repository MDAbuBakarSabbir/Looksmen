<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\Admin\OrderManageController;

Schedule::call(function () {
    try {
        $controller = resolve(OrderManageController::class);
        $controller->updateStatuses();
    } catch (\Exception $e) {
        \Log::error('Courier status update schedule error: ' . $e->getMessage());
    }
})->daily();
