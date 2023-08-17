<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Imdhemy\Purchases\Http\Controllers\ServerNotificationController;

Route::post('/liap/notifications', ServerNotificationController::class)
    ->name('liap.serverNotifications');
