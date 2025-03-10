<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0]; // get subdomain from host

        // set subdomain and company name to request attributes
        // $request->attributes->set('subdomain', $subdomain);
        switch ($subdomain) {
            case 'ptbkt':
                session(['app_company' => 'PT. Berdikari Karya Tunggal']);
                break;
            case 'ptmk':
                session(['app_company' => 'PT. Mitra Karya Ahesluda']);
                break;
            default:
                session(['app_company' => null]);
                break;
        }

        return $next($request);
    }
}
