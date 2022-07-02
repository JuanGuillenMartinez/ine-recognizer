<?php

namespace App\Exceptions;

use App\Helpers\JsonResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function(UnauthorizedException $e) {
            return JsonResponse::sendError('No cuenta con los permisos para realizar la accion', 500);
        });
        $this->renderable(function(WrongImageUrl $e) {
            return JsonResponse::sendError($e->getMessage(), $e->getCode(), ['description' => 'Aunque el servicio puede procesar imágenes en vertical, lo recomendable es que enviar imágenes en horizontal y con buena calidad']);
        });
        $this->renderable(function(RequestLimitReached $e) {
            return JsonResponse::sendError($e->getMessage(), $e->getCode(), ['description' => 'Ha alcanzado el limite de peticiones permitidas por este usuario. Contacte con el administrador para aumentar este limite.']);
        });
        $this->renderable(function(UserIsBanned $e) {
            return JsonResponse::sendError($e->getMessage(), $e->getCode(), ['description' => 'El usuario ha sido dado de baja del sistema. Contacte con el administrador.']);
        });
        $this->renderable(function(AzureFaceApiException $e) {
            $message = json_decode($e->getMessage());
            return JsonResponse::sendError($message->code, $e->getCode(), ['description' => $message->message]);
        });
        $this->renderable(function(WrongOCRLecture $e) {
            $message = json_decode($e->getMessage());
            return JsonResponse::sendError('La información extraída mediante la lectura OCR es incorrecta. Favor de tomar la fotografía de nuevo. Tome en cuenta las consideraciones descritas en la documentación', $e->getCode(), ['field_failed' => $message->field]);
        });
    }
}
