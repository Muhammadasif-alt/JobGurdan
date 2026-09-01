<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Canonical URL middleware — Hostinger CDN bypasses .htaccess, so we enforce
 * www → non-www redirect + security headers at the Laravel level.
 */
class CanonicalRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        $enforceCanonicalHost = app()->environment('production');

        // 1. Redirect www → non-www (301 permanent)
        if ($enforceCanonicalHost && str_starts_with($request->getHost(), 'www.')) {
            $newHost = substr($request->getHost(), 4);
            $newUrl = $request->getScheme().'://'.$newHost.$request->getRequestUri();

            return redirect($newUrl, 301);
        }

        // 2. Force HTTPS — checks both direct TLS and X-Forwarded-Proto from CDN/proxy.
        //    Skipped outside production so local http://127.0.0.1:8000 stays reachable.
        if ($enforceCanonicalHost && ! $request->isSecure() && $request->header('x-forwarded-proto') !== 'https') {
            return redirect('https://'.$request->getHost().$request->getRequestUri(), 301);
        }

        // 3. Strip trailing slash on non-root GET paths so /jobs/ and /jobs resolve
        //    to a single canonical URL — Semrush flagged the homepage variant otherwise.
        if ($request->isMethod('GET')) {
            $path = $request->getPathInfo();
            if ($path !== '/' && str_ends_with($path, '/')) {
                $newPath = rtrim($path, '/');
                $query = $request->getQueryString();
                $newUrl = $request->getSchemeAndHttpHost().$newPath.($query ? '?'.$query : '');

                return redirect($newUrl, 301);
            }
        }

        $response = $next($request);

        // 3. Add security headers (HSTS, XSS protection, etc.)
        //    HSTS is production-only — pinning it on localhost would force every
        //    later http://127.0.0.1 request in the browser to https and break dev.
        if ($enforceCanonicalHost) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
