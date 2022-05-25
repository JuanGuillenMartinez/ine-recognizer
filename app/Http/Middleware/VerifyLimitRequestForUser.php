<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\RequestLimit;
use Illuminate\Http\Request;
use App\Exceptions\RequestLimitReached;
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
        $user = $request->user();
        $result = $user->registerRequestMade($userRequest);
        if(!$result) {
            throw new RequestLimitReached('Ha alcanzado el limite de peticiones disponible. Comuníquese con su administrador.', 400);
        }
        return $next($request);
    }
}
