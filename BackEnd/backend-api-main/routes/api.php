<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Autentikasi
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Produk
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Keranjang
    Route::get('/cart', [CartController::class, 'index']);            // Lihat isi keranjang
    Route::post('/cart', [CartController::class, 'add']);             // Tambah item ke keranjang
    Route::put('/cart/{item}', [CartController::class, 'update']);    // Ubah jumlah item
    Route::delete('/cart/{item}', [CartController::class, 'remove']); // Hapus item dari keranjang
    // (opsional nanti bisa tambah DELETE /cart untuk hapus semua item sekaligus)

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/negotiations', [NegotiationController::class, 'index']);
    Route::post('/negotiations', [NegotiationController::class, 'store']);
    Route::put('/negotiations/{id}', [NegotiationController::class, 'updateStatus']); // opsional untuk seller/admin
});

Route::middleware('auth:sanctum')->get('/negotiations/incoming', [NegotiationController::class, 'incoming']);

Route::middleware('auth:sanctum')->put('/negotiations/{id}', [NegotiationController::class, 'updateStatus']);

// Untuk pembeli lihat riwayat
Route::get('/negotiations', [NegotiationController::class, 'index']);

// Untuk penjual lihat penawaran ke produk mereka
Route::get('/negotiations/incoming', [NegotiationController::class, 'incoming']);

Route::middleware('auth:sanctum')->post('/orders/{id}/upload-proof', [OrderController::class, 'uploadProof']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders/{id}/upload-proof', [OrderController::class, 'uploadProof']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
});

Route::middleware(['auth:sanctum', 'role:reseller'])->post('/products', [ProductController::class, 'store']);

Route::middleware(['auth:sanctum'])->get('/admin/orders', [OrderController::class, 'adminIndex']);


Route::middleware('auth:sanctum')->put('/orders/{id}/complete', [OrderController::class, 'markAsDone']);

