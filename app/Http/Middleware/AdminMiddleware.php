<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use InertiaToast\Facades\Toast;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            Toast::error("Please log in to continue.");
            return redirect()->route('admin.login');
        }

        if ($request->user()->role !== 'admin') {
            abort(403, 'You do not have access to the admin area.');
        }

        return $next($request);
    }
}
