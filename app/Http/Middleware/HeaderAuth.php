<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HeaderAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(isset($_SERVER['HTTP_USERNAME']) && isset($_SERVER['HTTP_PASSWORD'])){
            $username=$_SERVER['HTTP_USERNAME'];
            $password=$_SERVER['HTTP_PASSWORD'];
       }else{
           $username='';
           $password='';
       }
      
      
       if($username == 'dom-properties' && md5($password) ==  md5('Dom@Admin123') ){
           return $next($request);
       }else{
            $result['code']     =   -2;
           $result['message']  =   'Access Denied';
           $result['data']     =   [];
           $mainResult[]=$result;
           return response()->json($mainResult); 
       }
      
    }
}
