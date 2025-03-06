<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (!Auth::check()) {
        return redirect()->route('login'); // Chuyển hướng nếu chưa đăng nhập
    }

    if (Auth::user()->userType === 'user') {
        return $next($request);
    }

    abort(403, 'Unauthorized'); // Trả về lỗi nếu không phải user
}

}
