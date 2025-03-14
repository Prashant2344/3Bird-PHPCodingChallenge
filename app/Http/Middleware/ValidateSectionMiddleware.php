<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ValidateSectionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $section = $request->route('section');

        Log::info('Received section: ' . $section);
        
        // Allow only lowercase letters and hyphens
        if (!preg_match('/^[a-z-]+$/', $section)) {
            Log::warning('Invalid section name provided: ' . $section);

            return response()->json([
                'error' => 'Invalid section name. Use lowercase letters and hyphens only.'
            ], 400);
        }

        return $next($request);
    }
}
