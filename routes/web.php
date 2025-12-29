<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::get('/order/{record}/invoice', function (Order $record) {
    $pdf = Pdf::loadView('pdf.invoice', ['order' => $record]);
    return $pdf->download('Invoice-Order-' . $record->id . '.pdf');
})->name('order.invoice');