<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\RequestLimit;
use Illuminate\Http\Request;
use App\Exceptions\RequestLimitReached;
use App\Jobs\PersistRequestOnLogJob;
use App\Models\Request as ModelsRequest;

class VerifyLimitRequestForUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $name)
    {
        $userRequest = ModelsRequest::where('name', $name)->first();
        if(!isset($userRequest)) {
            throw new RequestLimitReached('No se encuentran permisos registrados para realizar peticiones. Comuníquese con su administrador.', 400);
        }
        $user = $request->user();
        $result = $user->registerRequestMade($userRequest);
        if(!$result) {
            throw new RequestLimitReached('Ha alcanzado el limite de peticiones disponible. Comuníquese con su administrador.', 400);
        }
        $this->persistRequest($request, $user);
        return $next($request);
    }

    private function persistRequest($request, $user) {
        $data = [
            'user_id' => $user->id,
            'token_used' => $request->bearerToken(),
            'ip_address' => $request->ip(),
            'url_requested' => $request->url(),
        ];
        PersistRequestOnLogJob::dispatch($data)->onQueue('default');
    }
}
