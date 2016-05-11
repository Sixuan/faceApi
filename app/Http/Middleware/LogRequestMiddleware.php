<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 5/11/16
 * Time: 7:46 PM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LogRequestMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        Log::info('app.requests', [
            'url' => $request->getUri(),
            'request' => $request->all(),
            'response' => $response->getContent()
        ]);
    }
}