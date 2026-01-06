<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EditorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('editor_auth')) {
            return redirect()->route('editor.login');
        }

        return $next($request);
    }
}