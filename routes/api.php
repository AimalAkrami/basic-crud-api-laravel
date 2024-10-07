<?php

use App\Http\Controllers\BlogController;
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
Route::post('/posts',[BlogController::class, 'createBlog']);
Route::put('/posts/{id}', [BlogController::class, 'updateBlog']);
Route::delete('posts/{id}', [BlogController::class, 'deleteBlog']);
Route::get('/posts/{id}', [BlogController::class, 'findOneBlog']);
Route::get('/posts', [BlogController::class, 'findAll']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

