<?php

namespace App\Helpers;

use Illuminate\Pagination\CursorPaginator;

class JsonResponse
{
    /**
     * Make a successfully response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendResponse($result, $message = 'Request successfully completed', $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, $code);
    }

    public static function sendPaginateResponse($result, $message = 'Request successfully completed', $code = 200)
    {
        if (isset($result->resource)) {
            $paginator = $result->resource->toArray();
        }
        if (isset($paginator['data'])) {
            $data = $paginator['data'];
            unset($paginator['data']);
        }
        $response = [
            'success' => true,
            'message' => $message,
            'data' => isset($data) ? $data : [],
            'links' => isset($paginator) ? $paginator : null,
        ];
        return response()->json($response, $code);
    }

    public static function sendCursorPaginateResponse($result, $totalRows, $message = 'Request successfully completed', $code = 200)
    {
        if (isset($result->resource)) {
            $paginator = $result->resource->toArray();
        }
        if (isset($paginator['data'])) {
            $data = $paginator['data'];
            unset($paginator['data']);
            $paginator['total'] = $totalRows;
        }
        $response = [
            'success' => true,
            'message' => $message,
            'data' => isset($data) ? $data : [],
            'links' => isset($paginator) ? $paginator : null,
        ];
        return response()->json($response, $code);
    }


    /**
     * Return a error response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendError($errorMessage = 'An error has occurred', $code = 202, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $errorMessage,
            'data' => [
                'errors' => $errors
            ],
        ];

        return response()->json($response, $code);
    }
}
