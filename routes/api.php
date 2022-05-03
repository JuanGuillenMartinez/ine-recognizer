<?php

use App\Helpers\JsonResponse;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FaceApiController;

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
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/analyze/documents/ine/front', [DocumentController::class, 'analyzeFrontIne']);
    Route::post('/analyze/documents/ine/back', [DocumentController::class, 'analyzeBackIne']);
    Route::post('/analyze/images/face-api/detect', [FaceApiController::class, 'detectFace']);
    Route::post('/analyze/images/face-api/verify/ine', [FaceApiController::class, 'verifyPhotoWithIne']);
});
