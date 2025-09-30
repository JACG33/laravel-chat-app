<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\UsuariosController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // CHAT
    Route::resource('/chat', ChatController::class)->names('chat');
    Route::get("/chat/registrar/{usuario}",[ChatController::class,'crearChat'])->name('chat.registrar');
    Route::get("/chat/conversacion/{id_chat}",[ChatController::class,'conversacion'])->name('chat.conversacion');

    // USUARIOS
    Route::resource('/usuarios', UsuariosController::class)->names('usuarios');

    Route::post('/chat/send/{id_chat}', [ChatController::class, 'sendMessage']);
});

require __DIR__ . '/auth.php';
