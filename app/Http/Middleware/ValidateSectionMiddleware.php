<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSectionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $section = $request->route('section');

        // Allow only lowercase letters and hyphens
        if (!preg_match('/^[a-z-]+$/', $section)) {
            return response()->json([
                'error' => 'Invalid section name. Use lowercase letters and hyphens only.'
            ], 400);
        }

        return $next($request);
    }
}
