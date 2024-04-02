<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ResponseAPI;

class UserAccess
{
    use ResponseAPI;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userType): Response
    {
        if(auth()->user()->type == $userType){
            return $next($request);
        }
          
        return $this->forbiddenResponse('', 'You do not have permission to access for this page.');
    }
}
