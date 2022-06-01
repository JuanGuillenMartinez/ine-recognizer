<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\Log\LogRequestResource;
use App\Models\LogRequest;
use Illuminate\Http\Request;

class LogRequestController extends Controller
{
    public function index()
    {
        $logs = LogRequest::all();
        return JsonResponse::sendResponse(LogRequestResource::collection($logs));
    }
}
