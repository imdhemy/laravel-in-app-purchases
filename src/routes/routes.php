<?php

use Illuminate\Support\Facades\Route;
use Imdhemy\Purchases\Http\Controllers\DeveloperNotificationController;

Route::post('/purchases/subscriptions/google', [DeveloperNotificationController::class, 'google'])->name('purchase.developerNotifications.google');
