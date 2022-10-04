<?php

namespace App\Http\Middleware;

use App\Models\MainUsers;
use Closure;
use Illuminate\Http\Request;

class LoginAuth
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
        
        if($request->input('is_checking_other') != 1) {

            if ($request->user_id) {
                $user = MainUsers::where('id', $request->user_id)->where('is_otp_varified', '=', 1)->first();
    
                if ($user) {
    
                    if ($user->status == 0) {
                        $result['code']     = (string) -3;
                        $result['message']  = 'inactive_account';
                        $result['result']   = [];
    
                        $mainResult[] = $result;
                        return response()->json($mainResult);
                    }
    
                    if ($user->status == 2) {
                        $result['code']     = (string) -2;
                        $result['message']  = 'account_deleted_contact_to_admin';
                        $result['result']   = [];
    
                        $mainResult[] = $result;
                        return response()->json($mainResult);
                    }
                    
                    $token = $request->input('token') ?: "";
                    if($user->remember_token != $token){
                        $result['code']     = (string) -7;
                        $result['message']  = 'invalid_token';
                        $result['result']   = [];
    
                        $mainResult[]=$result;
                        return response()->json($mainResult); 
                    }
                }else{
                    $result['code']     = (string) -7;
                    $result['message']  = 'account_not_found';
                    $result['result']   = [];
    
                    $mainResult[] = $result;
                    return response()->json($mainResult);
                }
            } 
            else{
                $result['code']     = (string) -7;
                $result['message']  = 'login_required';
                $result['result']   = [];
    
                $mainResult[] = $result;
                return response()->json($mainResult);
            }
        }
        
        return $next($request);
    }
}
