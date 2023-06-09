<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtMiddleware
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$guard = null)
    {
        if($guard != null){
            // auth()->shouldUse($guard);
            Auth::shouldUse($guard);
            $token = $request->header('auth-token');
            $request->headers->set('auth-token',(string) $token,true);
            // must define the auth-token in headers and set token to it //
            // $request->headers->set('Authorization', 'Bearer '.$token, true);


            try{
                $user = JWTAuth::parseToken()->authenticate();
            }catch(TokenExpiredException $e){
                return $this->returnError(401,'','token is Expired');
            }catch(JWTException $e){
                return $this->returnError('401','',$e->getMessage());
            }
        }
        else{
            return  $this -> returnError(401,'', 'token undefined');
        }
        return $next($request);
    }
}
