<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
