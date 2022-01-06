<?php
declare (strict_types=1);

namespace app\middleware;

class Response
{
    /**
     * @param $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        return $next($request)->header([
            'X-Powered-By' => 'ASP.NET',
            'Author' => 'AOAOSTAR/Pluto',
            'Home-Page' => 'www.aoaostar.com',
        ]);
    }
}
