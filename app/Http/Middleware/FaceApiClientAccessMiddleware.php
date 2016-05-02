<?php
/**
 * Created by PhpStorm.
 * User: sixuanliu
 * Date: 4/17/16
 * Time: 4:18 PM
 */

namespace App\Http\Middleware;

use App\Exceptions\ClientNotExistingException;
use App\Http\Controllers\Controller;
use App\Http\Models\ClientModelSql;
use Closure;
use Illuminate\Http\Response;

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
        $appKey = $request->input('app_key');
        $appSecret = $request->input('app_secret');

        try{
            $clientId = ClientModelSql::getInstance()->getClientId($appKey, $appSecret);
            Controller::setClientId($clientId);
        } catch (ClientNotExistingException $e) {
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