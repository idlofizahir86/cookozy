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
            // Jika permintaan JSON dan bukan rute API, kembalikan respons JSON dengan pesan kesalahan
            if (str_starts_with($request->path(), 'api')) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            // Jika bukan rute API, arahkan pengguna ke halaman login
            return route('login');
        } else {
            // Jika permintaan bukan JSON, arahkan pengguna ke halaman login
            return route('login');
        }
    }
}
