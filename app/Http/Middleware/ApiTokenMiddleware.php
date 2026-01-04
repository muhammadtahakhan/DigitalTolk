<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    // Define your hardcoded token here (change this to something secret!)
    protected $validToken = 'your-super-secret-hardcoded-token-123456';
    // protected $validToken = env('API_TOKEN', 'fallback-token');
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        

        // Optional: also support ?api_token=... in query string (for testing)
        if (!$token) {
            $token = $request->query('api_token');
            $token = $token ? 'Bearer ' . $token : null;
        }

        if ($token !== 'Bearer ' . $this->validToken) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}