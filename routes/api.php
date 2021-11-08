<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user_call', [UserController::class, 'getUserById']);
Route::post('login_call', [UserController::class, 'loginUser']);
Route::post('registration_call', [UserController::class, 'registerNewUser']);
Route::post('update_image_call', [UserController::class, 'updateImage']);
Route::post('update_profile_call', [UserController::class, 'updateProfile']);
Route::post('reset_password_request', [UserController::class, 'resetPasswordRequest']);
Route::post('reset_password', [UserController::class, 'resetPassword']);
Route::get('blogs_list', [BlogController::class, 'indexBlog']);
Route::post('add_product', [ProductController::class, 'store']);
Route::post('update_product', [ProductController::class, 'update']);
Route::get('product_list', [ProductController::class, 'indexProduct']);
Route::post('my_products', [ProductController::class, 'myProduct']);
Route::post('delete_product', [ProductController::class, 'destroy']);
Route::get('labours_list', [UserController::class, 'laboursIndex']);
Route::get('diseases', [DiseaseController::class, 'show']);
