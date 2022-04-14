<?php
namespace App\Helpers;
class JsonResponse {
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