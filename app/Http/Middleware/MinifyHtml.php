<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $content = $response->getContent();

            // Minify HTML (hapus spasi, komentar, dan karakter tak perlu)
            $minified = preg_replace([
                '/\>[^\S ]+/s',     // Hapus spasi di antara tag HTML
                '/[^\S ]+\</s',     // Hapus spasi sebelum tag HTML
                '/(\s)+/s',         // Kurangi spasi berlebih
                '/<!--(.|\s)*?-->/' // Hapus komentar HTML
            ], ['>', '<', '\\1', ''], $content);

            $response->setContent($minified);
        }

        return $response;
    }
}
