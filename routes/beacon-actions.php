<?php

use Illuminate\Support\Facades\Route;
use RohitShakya\Beacon\Http\Controllers\NotificationActionController;

Route::post('/notifications/{id}/read', [NotificationActionController::class, 'read'])
    ->name('beacon.notifications.read');

Route::post('/notifications/{id}/unread', [NotificationActionController::class, 'unread'])
    ->name('beacon.notifications.unread');

Route::post('/notifications/read-all', [NotificationActionController::class, 'readAll'])
    ->name('beacon.notifications.readAll');

Route::delete('/notifications/{id}', [NotificationActionController::class, 'delete'])
    ->name('beacon.notifications.delete');
