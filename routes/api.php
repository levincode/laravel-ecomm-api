<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\PasswordController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Forgot and reset password routes
Route::post('forgot', [PasswordController::class,'forgot']);
Route::post('reset', [PasswordController::class,'reset']);

Route::get('get-category', [FrontendController::class, 'category']);
Route::get('fetch-products/{slug}', [FrontendController::class, 'product']);
Route::get('view-product-detail/{category_slug}/{product_slug}', [FrontendController::class, 'viewproduct']);

Route::middleware(['auth:sanctum', 'isAPIAdmin'])->group(function () {

    Route::get('/checkingAuthenticated', function () {
        return response()->json(['message' => 'You are in', 'status' => 200], 200);
    });

    // Product
    Route::post('store-product', [ProductController::class, 'store']);
    // Route::get('view-product', [ProductController::class, 'index']);
    // Route::get('edit-product/{id}', [ProductController::class, 'edit']);
    // Route::put('update-product/{id}', [ProductController::class, 'update']);

    // Category
    Route::post('store-category', [CategoryController::class, 'store']);
    Route::get('view-category', [CategoryController::class, 'index']);
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
    Route::put('update-category/{id}', [CategoryController::class, 'update']);
    Route::delete('delete-category/{id}', [CategoryController::class, 'destroy']);
    Route::get('all-category', [CategoryController::class, 'allcategory']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
