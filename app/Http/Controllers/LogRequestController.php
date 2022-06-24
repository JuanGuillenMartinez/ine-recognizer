<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\Log\LogRequestCollectionResource;
use App\Http\Resources\Log\LogRequestResource;
use App\Models\LogRequest;
use Illuminate\Http\Request;

class LogRequestController extends Controller
{
    public function index(Request $request)
    {
        $perPage = isset($request->perPage) ? $request->perPage : 10;
        $logs = LogRequest::paginate($perPage);
        return JsonResponse::sendPaginatedResponse(LogRequestResource::collection($logs));
    }
}
