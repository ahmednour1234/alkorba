<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CoffeeController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\ExtensionController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\StatusController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    Route::post('/logout', [AuthController::class, 'logout']);
    //passwordupdate
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.reset');
Route::post('/password/verify-code', [PasswordResetController::class, 'verifyResetCode']);
Route::post('/password/sendSmsForPasswordUpdate', [PasswordResetController::class, 'sendSmsForPasswordUpdate']);
//category
Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category', [CategoryController::class, 'store']);
Route::put('/category/{id}', [CategoryController::class, 'update']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
//products
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::post('/product', [ProductController::class, 'store']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::delete('/product/{id}', [ProductController::class, 'destroy']);
Route::get('/products/search', [ProductController::class, 'search']);
//coffee
Route::get('/coffee', [CoffeeController::class, 'index']);
Route::get('/coffee/{id}', [CoffeeController::class, 'show']);
Route::post('/coffee', [CoffeeController::class, 'store']);
Route::put('/coffee/{id}', [CoffeeController::class, 'update']);
Route::delete('/coffee/{id}', [CoffeeController::class, 'destroy']);
//feedback
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::get('/feedback/{id}', [FeedbackController::class, 'show']);
Route::post('/feedback', [FeedbackController::class, 'store']);
Route::put('/feedback/{id}', [FeedbackController::class, 'update']);
Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']);
//extintion
Route::get('/extensions', [ExtensionController::class, 'index']);
Route::post('/extensions', [ExtensionController::class, 'store']);
Route::put('/extensions/{product_id}/{extension_id}', [ExtensionController::class, 'update']);
Route::delete('/extensions/{product_id}/{extension_id}', [ExtensionController::class, 'destroy']);
//orders
Route::post('/orders', [OrderController::class, 'store']);
Route::put('/orders/{orderId}', [OrderController::class, 'update']);
Route::delete('/orders/{orderId}', [OrderController::class, 'destroy']);
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{orderId}', [OrderController::class, 'show']);
Route::get('/users/{userId}/orders', [OrderController::class, 'getOrdersByUserId']);
Route::post('/orders/search', [OrderController::class, 'search']);
//address
Route::get('/addresses', [AddressController::class, 'index']);
Route::post('/addresses', [AddressController::class, 'store']);
Route::put('/addresses/{id}', [AddressController::class, 'update']);
Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
//Delivery
Route::get('/deliveries', [DeliveryController::class, 'index']);
Route::post('/deliveries', [DeliveryController::class, 'store']);
Route::put('/deliveries/{id}', [DeliveryController::class, 'update']);
Route::delete('/deliveries/{id}', [DeliveryController::class, 'destroy']);
//status
Route::get('/', [StatusController::class, 'index']); // Get all statuses
Route::post('/', [StatusController::class, 'store']); // Create a new status
Route::put('/{id}', [StatusController::class, 'update']); // Update an existing status
Route::delete('/{id}', [StatusController::class, 'destroy']);
});
//register
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//googel
Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle']);
Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);
//facebook
Route::get('/facebook/redirect', [GoogleLoginController::class, 'redirectToFacebook']);
Route::get('/facebook/callback', [GoogleLoginController::class, 'handleFacebookCallback']);

