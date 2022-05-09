<?php

use App\Helpers\JsonResponse;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FaceApi\PersonController;
use App\Http\Controllers\FaceApi\PersonGroupController;
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

//* FACE API ENDPOINTS
Route::post('/face-api/detect', [FaceApiController::class, 'detectFace']);
Route::post('/face-api/analyze/face2face', [FaceApiController::class, 'verifyFaceToFace']);
Route::post('/face-api/analyze/face2person', [FaceApiController::class, 'face2person']);

//* FACE API TRAINING ENDPOINTS
//* Person group
Route::post('/face-api/persongroups/{personGroupId}', [PersonGroupController::class, 'create']);
Route::get('/face-api/persongroups/{personGroupId}/training', [PersonGroupController::class, 'trainingStatus']);
Route::post('/face-api/persongroups/{personGroupId}/train', [PersonGroupController::class, 'train']);

//* Person
Route::post('/face-api/persongroups/{personGroupId}/persons', [PersonController::class, 'create']);
Route::get('/face-api/persongroups/{personGroupId}/persons', [PersonController::class, 'listAll']);
Route::post('/face-api/persongroups/{personGroupId}/persons/{personId}/persistedFaces', [PersonController::class, 'addFace']);

//* FORM RECOGNIZER ENDPOINTS
Route::post('/analyze/documents/ine/front', [DocumentController::class, 'analyzeFrontIne']);
Route::post('/analyze/documents/ine/back', [DocumentController::class, 'analyzeBackIne']);

// Route::middleware(['auth:sanctum'])->group(function () {
//     //* FACE API ENDPOINTS
//     Route::post('/face-api/detect', [FaceApiController::class, 'detectFace']);
//     Route::post('/face-api/verify/face2face', [FaceApiController::class, 'verifyFaceToFace']);
//     Route::post('/face-api/verify/face2person', [FaceApiController::class, 'face2person']);

//     //* FORM RECOGNIZER ENDPOINTS
//     Route::post('/analyze/documents/ine/front', [DocumentController::class, 'analyzeFrontIne']);
//     Route::post('/analyze/documents/ine/back', [DocumentController::class, 'analyzeBackIne']);
// });
