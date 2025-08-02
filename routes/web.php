<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactFormController;                    // 公開側
use App\Http\Controllers\Admin\ContactController as AdminContact;  // 管理側

// 公開フロー
Route::get('/',         [ContactFormController::class, 'index'])->name('contacts.index');
Route::post('/confirm', [ContactFormController::class, 'confirm'])->name('contacts.confirm');
Route::post('/thanks',  [ContactFormController::class, 'store'])->name('contacts.store');


// 管理画面（ログイン必須）
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                           [AdminContact::class, 'index'])->name('contacts.index');
    Route::get('/export',                     [AdminContact::class, 'export'])->name('contacts.export');
    Route::get('/contacts/{contact}',         [AdminContact::class, 'show'])->name('contacts.show');
    Route::get('/contacts/{contact}/json',    [AdminContact::class, 'showJson'])->name('contacts.showJson');
    Route::delete('/contacts/{contact}',      [AdminContact::class, 'destroy'])->name('contacts.destroy');
});
