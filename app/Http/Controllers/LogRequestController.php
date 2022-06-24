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
        return JsonResponse::sendPaginatedResponse(LogRequestResource::collection($logs));
    }
}
