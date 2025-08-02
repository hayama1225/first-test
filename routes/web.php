<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ContactFormController;                    // 公開側
use App\Http\Controllers\Admin\ContactController as AdminContact;  // 管理側

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',        [AdminContact::class, 'index'])->name('contacts.index');
    // 検索, CSV, 詳細/モーダル は後で追加
});

// 公開フロー
Route::get('/',         [ContactFormController::class, 'index'])->name('contacts.index');
Route::post('/confirm', [ContactFormController::class, 'confirm'])->name('contacts.confirm');
Route::post('/thanks',  [ContactFormController::class, 'store'])->name('contacts.store');

// 管理画面（必要なら middleware('auth') を付与）
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminContact::class, 'index'])->name('contacts.index');
});