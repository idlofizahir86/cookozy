<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FirebaseAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah permintaan berasal dari API atau aplikasi web
        if ($request->expectsJson()) {
            // Jika permintaan adalah API, periksa apakah pengguna sudah terotentikasi
            if (Auth::check()) {
                return $next($request);
            } else {
                // Jika pengguna tidak terotentikasi, kembalikan respons JSON dengan status 401
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            // Jika permintaan adalah aplikasi web, lakukan pengecekan terhadap session
            $uid = session('uid');
            if ($uid) {
                return $next($request);
            } else {
                // Jika tidak ada UID pengguna dalam session, hapus session dan arahkan ke halaman login
                session()->flush();
                return redirect('/login');
            }
        }
    }
}

