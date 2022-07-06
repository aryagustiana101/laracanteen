<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthenticationController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/user', [UserController::class, 'show']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/image/{image_name}', [ProductController::class, 'image']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order_code}', [OrderController::class, 'show']);

    Route::get('/payments/{payment_credentials}', [PaymentController::class, 'show']);
    Route::get('/payments/image/{image_name}', [PaymentController::class, 'image']);
});

Route::group(['middleware' => ['auth:sanctum', 'ability:manage-products,take-orders,decline-orders,ready-orders,give-orders']], function () {

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::patch('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'delete']);

    Route::put('/orders/{order_code}/take', [OrderController::class, 'take']);
    Route::patch('/orders/{order_code}/take', [OrderController::class, 'take']);
    Route::put('/orders/{order_code}/decline', [OrderController::class, 'decline']);
    Route::patch('/orders/{order_code}/decline', [OrderController::class, 'decline']);
    Route::put('/orders/{order_code}/ready', [OrderController::class, 'ready']);
    Route::patch('/orders/{order_code}/ready', [OrderController::class, 'ready']);
    Route::put('/orders/{order_code}/give', [OrderController::class, 'give']);
    Route::patch('/orders/{order_code}/give', [OrderController::class, 'give']);
});

Route::group(['middleware' => ['auth:sanctum', 'ability:place-orders,cancel-orders']], function () {

    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order_code}/cancel', [OrderController::class, 'cancel']);
    Route::patch('/orders/{order_code}/cancel', [OrderController::class, 'cancel']);
});

Route::group(['middleware' => ['auth:sanctum', 'ability:manage-payments']], function () {

    Route::put('/payments/{payment_code}/pay', [PaymentController::class, 'pay']);
    Route::patch('/payments/{payment_code}/pay', [PaymentController::class, 'pay']);
});

