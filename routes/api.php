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
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

//* FACE API ENDPOINTS
Route::post('/faceapi/detect', [FaceApiController::class, 'detectFace']);
Route::post('/faceapi/analyze/face2face', [FaceApiController::class, 'verifyFaceToFace']);
Route::post('/faceapi/analyze/face2person', [FaceApiController::class, 'verifyFaceToPerson']);

//* FACE API TRAINING ENDPOINTS
//* Person group
Route::get('/faceapi/persongroups', [PersonGroupController::class, 'list']);
Route::post('/faceapi/persongroups/{personGroupId}', [PersonGroupController::class, 'create']);
Route::get('/faceapi/persongroups/{personGroupId}/training', [PersonGroupController::class, 'trainingStatus']);
Route::post('/faceapi/persongroups/{personGroupId}/train', [PersonGroupController::class, 'train']);
Route::delete('/faceapi/persongroups/{personGroupId}', [PersonGroupController::class, 'delete']);

//* Person
Route::post('/faceapi/persongroups/{personGroupId}/persons', [PersonController::class, 'create']);
Route::get('/faceapi/persongroups/{personGroupId}/persons', [PersonController::class, 'listAll']);
Route::post('/faceapi/persongroups/{personGroupId}/persons/{personId}/persistedFaces', [PersonController::class, 'addFace']);
Route::delete('/faceapi/persongroups/{personGroupId}/persons/{personId}', [PersonController::class, 'delete']);
Route::delete('/faceapi/persongroups/{personGroupId}/persons/{personId}/persistedFaces/{persistedFaceId}', [PersonController::class, 'deleteFace']);

//* FORM RECOGNIZER ENDPOINTS
Route::post('/analyze/documents/ine/front', [DocumentController::class, 'analyzeFrontIne']);
Route::post('/analyze/documents/ine/back', [DocumentController::class, 'analyzeBackIne']);


Route::middleware(['auth:sanctum'])->group(function () {

});
