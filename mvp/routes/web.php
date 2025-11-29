<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;

// カレンダーの表示
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
// カレンダーへの反映
Route::get('/task/events', [TaskController::class, 'getEvents'])->name('task.events');


// タスクの作成・保存・一覧表示・個別表示機能・編集機能・消去機能
Route::resource('task', TaskController::class);


Route::get('/', function () {
    return view('welcome');
})->name('home');



Route::get('/dashboard', function () {
    return redirect('/calendar');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
