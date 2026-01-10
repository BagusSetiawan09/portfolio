<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DocumentController; // <-- Controller Baru

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// Group Route khusus Dokumen (Perlu Login)
Route::middleware(['auth'])->prefix('admin/documents')->group(function () {
    
    // Print BAST
    Route::get('/bast/{bast}/print', [DocumentController::class, 'bast'])->name('bast.print');

    // Print Surat Resmi
    Route::get('/letter/{letter}/print', [DocumentController::class, 'letter'])->name('letter.print');

    // Download Proposal PDF
    Route::get('/proposal/{proposal}/download', [DocumentController::class, 'proposal'])->name('proposal.download');

    // Download Invoice
    Route::get('/invoice/{order}', [DocumentController::class, 'invoice'])->name('order.invoice');
    
});