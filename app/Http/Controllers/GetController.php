<?php

namespace App\Http\Controllers; 
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Carbon\Carbon;
use DB;  
use App\Models\User; 
use Auth;
use Hash;
use File;
use Helper;
use Illuminate\Http\Request;

class GetController extends Controller
{ 
    /**
     * Create a new controller instance.
     *
     * @return void
     */ 
    
    public function VerifyEmail($encode_id)
    {     
        $id = base64_decode($encode_id);  
        User::where('id',$id)->update(['email_verified' => 1]);
        return view("dashboard.verify_email");
    }
}
