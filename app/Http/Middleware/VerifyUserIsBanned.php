<?php

namespace App\Http\Middleware;

use App\Exceptions\UserIsBanned;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyUserIsBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!isset($user->banned_at)) {
            return $next($request);
        }
        throw new UserIsBanned('El usuario se encuentra inactivo.', 500);
    }
}
