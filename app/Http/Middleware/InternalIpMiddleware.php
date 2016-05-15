<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/15/16
 * Time: 5:23 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class InternalIpMiddleware
{

    private $trustedIps = ['148.75.31.12', '127.0.0.1'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->getClientIp();

        if(!in_array($ip, $this->trustedIps)) {
            return new Response(
                [
                    'status' => 'Unauthorized',
                    'message' => 'Invalid app_key OR app_secret'
                ],
                401
            );
        }
        
        return $next($request);
    }
}