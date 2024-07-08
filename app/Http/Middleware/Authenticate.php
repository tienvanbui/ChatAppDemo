<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            TODO: // use response trait
            return response()->json(
                [
                    'message' => __('Unauthorized'),
                    'status_code' => 401
                ],
                401
            );
        }

        if ($request->routeIs(['admin.*', 'api.admin.*'])) {
            return route('admin.login');
        } else {
            return route('login');
        }
    }
}
