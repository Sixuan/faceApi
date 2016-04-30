<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 4/17/16
 * Time: 4:18 PM
 */

namespace App\Http\Middleware;

use Closure;

class FaceApiClientAccessMiddleware
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
        return $next($request);
    }
}