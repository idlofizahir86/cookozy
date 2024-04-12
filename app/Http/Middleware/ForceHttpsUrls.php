<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttpsUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Ubah semua URL HTTP menjadi HTTPS
        $content = $response->getContent();
        $content = str_replace('http://', 'https://', $content);

        // Set konten yang telah dimodifikasi kembali ke respons
        $response->setContent($content);

        return $response;
    }
}
