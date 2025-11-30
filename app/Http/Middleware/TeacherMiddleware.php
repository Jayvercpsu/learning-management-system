<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->isTeacher()) {
            abort(403, 'Unauthorized access.');
        }

        if (!$user->is_approved) {
            return redirect()->route('login')
                ->with('error', 'Your account is pending admin approval.');
        }

        return $next($request);
    }
}
