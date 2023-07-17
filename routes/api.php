<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController ;
use App\Models\Category;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });




Route::apiResource( 'brands' , BrandController::class);
Route::get('/brands/{brand}/products' , [BrandController::class , 'products']);

Route::apiResource( 'categories' , CategoryController::class);
Route::get('/categories/{category}/products' , [CategoryController::class , 'products']);


Route::get('/categories/{category}/children' , [CategoryController::class , 'children']);
Route::get('/categories/{category}/parent' , [CategoryController::class , 'parent']);

Route::apiResource( 'products' , ProductController::class);
