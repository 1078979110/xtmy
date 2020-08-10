<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Salelist;
class apiToken
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
        if(Auth::guard('api')->guest()){
            return response()->json(['status'=>401,'msg'=>'token is null']);
        }
        return $next($request);
    }
    
    public function username()
    {
        return 'telephone';
    }
}
