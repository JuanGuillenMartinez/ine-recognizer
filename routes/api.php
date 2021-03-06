<?php

use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FaceApiController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Person\PersonController;
use App\Http\Controllers\Commerce\CommerceController;
use App\Http\Controllers\FaceApi\PersonGroupController;
use App\Http\Controllers\FaceApi\PersonController as PersonGroupPersonController;
use App\Http\Controllers\LogRequestController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyUserIsBanned;

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



Route::middleware(['auth:sanctum', 'role:super-admin', 'active'])->group(function () {
    /** 
        REGISTER USER ENDPOINT 
        */
    Route::post('/auth/register', [AuthController::class, 'register']);
    
    /** 
        REGISTER COMMERCE ENDPOINT 
     */
    Route::post('/commerces', [CommerceController::class, 'create']);
    
    /**
        USER ENDPOINTS
        */
        Route::apiResource('/users', UserController::class);
        Route::put('/users/{userId}/limits', [UserController::class, 'updateLimits']);
        Route::get('/users/{userId}/credentials', [UserController::class, 'credentials']);
        Route::post('/users/{userId}/token', [UserController::class, 'generateToken']);
        Route::post('/users/{userId}/ban', [UserController::class, 'banUser']);
        Route::post('/users/{userId}/unban', [UserController::class, 'unbanUser']);
        
        /**
        LOG ENDPOINTS
        */
        Route::get('/logs', [LogRequestController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'role:super-admin|user', 'active'])->group(function () {
    /** 
     COMMERCE ENDPOINTS 
     */
    Route::post('/commerces/{commerceId}/persons', [CommerceController::class, 'addPerson'])->middleware('limit:register-person');
    Route::get('/commerces/{commerceId}/persongroup', [CommerceController::class, 'faceapiPersonGroupId']);
    Route::post('/commerces/{commerceId}/search/persons', [PersonController::class, 'personInformation']);
    Route::post('/commerces/{commerceId}/persons/{personId}/verify', [PersonController::class, 'analyzeFaceToPerson'])->middleware('limit:verify_identity');
    Route::get('/commerces/{commerceId}/persons/{personId}/cic', [PersonController::class, 'backIneInformation']);
    /** 
     FACE API ENDPOINTS 
     */
    Route::post('/faceapi/detect', [FaceApiController::class, 'detectFace']);
    Route::post('/faceapi/analyze/face2face', [FaceApiController::class, 'verifyFaceToFace']);
    Route::post('/faceapi/analyze/face2person', [FaceApiController::class, 'verifyFaceToPerson']);
    /** 
     FORM RECOGNIZER ENDPOINTS 
     */
    Route::post('/analyze/documents/ine/front', [DocumentController::class, 'analyzeFrontIne'])->middleware('limit:verify_identity');
    Route::post('/analyze/documents/ine/back', [DocumentController::class, 'analyzeBackIne'])->middleware('limit:verify_identity');
});

/** 
 PUBLIC LOGIN ENDPPOINT 
 */
Route::post('/auth/login', [AuthController::class, 'login']);



//// Route::get('/faceapi/persongroups', [PersonGroupController::class, 'list']);
//// Route::post('/faceapi/persongroups/{personGroupId}', [PersonGroupController::class, 'create']);
//// Route::get('/faceapi/persongroups/{personGroupId}/training', [PersonGroupController::class, 'trainingStatus']);
//// Route::post('/faceapi/persongroups/{personGroupId}/train', [PersonGroupController::class, 'train']);
//// Route::delete('/faceapi/persongroups/{personGroupId}', [PersonGroupController::class, 'delete']);
//// Route::post('/faceapi/persongroups/{personGroupId}/persons', [PersonGroupPersonController::class, 'create']);
//// Route::get('/faceapi/persongroups/{personGroupId}/persons', [PersonGroupPersonController::class, 'listAll']);
//// Route::post('/faceapi/persongroups/{personGroupId}/persons/{personId}/persistedFaces', [PersonGroupPersonController::class, 'addFace']);
//// Route::delete('/faceapi/persongroups/{personGroupId}/persons/{personId}', [PersonGroupPersonController::class, 'delete']);
//// Route::delete('/faceapi/persongroups/{personGroupId}/persons/{personId}/persistedFaces/{persistedFaceId}', [PersonGroupPersonController::class, 'deleteFace']);