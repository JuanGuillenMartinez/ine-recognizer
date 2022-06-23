<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\Log\LogRequestCollectionResource;
use App\Http\Resources\Log\LogRequestResource;
use App\Models\LogRequest;
use Illuminate\Http\Request;

class LogRequestController extends Controller
{
    public function index()
    {
        $logs = LogRequest::paginate(10);
        var_dump($logs->toArray());
        die;
        // return JsonResponse::sendResponse(new LogRequestCollectionResource($logs));
        return JsonResponse::sendResponse(LogRequestResource::collection($logs));
        // return JsonResponse::sendResponse($logs);
    }
}
