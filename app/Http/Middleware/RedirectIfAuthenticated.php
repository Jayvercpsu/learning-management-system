<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $targetRoute = match ((string) ($user->role ?? '')) {
                    'admin' => 'admin.dashboard',
                    'teacher' => 'teacher.dashboard',
                    'student' => 'student.dashboard',
                    default => null,
                };

                if ($targetRoute !== null && Route::has($targetRoute)) {
                    return redirect()->route($targetRoute);
                }

                if (Route::has('login')) {
                    return redirect()->route('login');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
