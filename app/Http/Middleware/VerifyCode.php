<?php

namespace App\Http\Middleware;

use App\Traits\responseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyCode
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
        if (Auth::guard()->check()) {
            if (Auth::user()->mobile_verified_at == null) {
                return $this->returnError($this->getErrorCode('mobile'), 'من فضلك ادخل تحقق من رقم الموبايل');
            }
        }
        return $next($request);
    }
}
