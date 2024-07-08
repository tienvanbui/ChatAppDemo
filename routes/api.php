<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(
    function () {
        Route::middleware(['auth'])->group(function () {
            Route::apiResource('users', UserController::class)->names('users');
            Route::get('/get-conversation-with-person/{id}', [ConversationController::class, 'getConversationWithPerson'])
                ->name('conversation.person');
            Route::put('/pin-chat-item/{id}', [ConversationController::class, 'pin'])->name('chats.pin');
            Route::put('/un-pin-chat-item/{id}', [ConversationController::class, 'unPin'])->name('chats.unpin');
            Route::apiResource('/chats', ConversationController::class)->names('conversations');
            Route::post('/messages/{conversation_id}', [MessageController::class, 'store'])->name('messages.store');
        });
    }
);
