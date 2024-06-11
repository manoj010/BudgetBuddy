<?php

use App\Http\Controllers\API\CategoryController\ExpenseCategoryController;
use App\Http\Controllers\API\CategoryController\IncomeCategoryController;
use App\Http\Controllers\API\CategoryController\LoanCategoryController;
use App\Http\Controllers\API\CategoryController\SavingCategoryController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

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

//LoginController
Route::post('/login', [LoginController::class, 'loginUser']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user-detail', [LoginController::class, 'userDetails']);
    Route::post('/logout', [LoginController::class, 'logoutUser']);

    Route::apiResource('income-category', IncomeCategoryController::class);
    Route::apiResource('expense-category', ExpenseCategoryController::class);
    Route::apiResource('saving-category', SavingCategoryController::class);
    Route::apiResource('loan-category', LoanCategoryController::class);
});

//UserController
Route::post('/register', [UserController::class, 'registerUser']);
