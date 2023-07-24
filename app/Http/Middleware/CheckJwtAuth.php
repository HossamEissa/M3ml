<?php

namespace App\Http\Middleware;

use App\Traits\responseTrait;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckJwtAuth
{
    use responseTrait;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $request->auth = $user;
        } catch (\Exception $e) {
            return $this->returnError($error = "", "من فضلك سجل الدخول أولا ");
        }
        return $next($request);
    }
}
