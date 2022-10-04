<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use Session;
use DB;
use Mail;
use Cookie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Setting;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = 'admin/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['status' => 1]);
    }

    public function showMainuserLoginForm()
    { 
        if (auth()->guard('admin_user')->check()) {
            return redirect()->route('adminHome');
        } else {
            return view('auth.login');
        }
    }


    public function adminLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ],
            [
                'email.exists' => 'These credentials do not match our records.',
            ]
        );
        $remember_me = $request->has('remember_me') ? true : false;

        $user = User::where('email',$request->email)->latest()->first(); 
        if(isset($user) && $user->status == 1 && $user->email_verified == 1){
            $user_role_id = UserRole::where('user_id',$user->id)->pluck('role_id')->toArray(); 

            $roles = Role::pluck('id')->toArray(); 

            $is_allowed = [];
            foreach ($roles as $role_key => $role_value) {
                if (in_array($role_value, $user_role_id)){
                    $is_allowed[] = 1;
                }    
            }  
            if (count($is_allowed) > 0 ){

                if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password'),'email_verified' => 1, 'status' => 1], $remember_me)) {
                    if ($remember_me == true) {
                        // echo "string";exit();
                        $minutes = 120;

                       Cookie::queue('admin_email',  $request->input('email'), $minutes);
                     Cookie::queue('admin_password', $request->input('password'), $minutes);
                    } else {
                        \Cookie::queue(\Cookie::forget('admin_email'));
                        \Cookie::queue(\Cookie::forget('admin_password'));
                    } 
                    
                    Auth::login($user, $remember_me);
                    return redirect(route('adminHome'));
                } else {

                    return back()->withInput($request->input())->with('error', 'Your email or password is invalid.');
                }
            }else{

                return back()->withInput($request->input())->with('error', 'Your are not authorised to access this.');
            }
        }else{
            if($user->status == 0){
                return back()->withInput($request->input())->with('error', 'Your account is in-active');
            }elseif($user->status == 2){
                return back()->withInput($request->input())->with('error', 'Your account is deleted');
            }elseif($user->email_verified != 1){
                return back()->withInput($request->input())->with('error', 'Email not verified. please verify your email.');
            } 
        }
          
        
    }


    public function forgotpass(Request $request)
    {
        if (auth()->guard('admin_user')->check()) {
            return redirect()->route('adminHome');
        } else {
            return view('auth.passwords.email');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mainuserforgot(Request $request)
    {
        // echo "<pre>";print_r($request->toArray());exit();
        $result = $this->validateRequest();
        $exists = User::where('email', '=', $result['email'])->where('status',1)->where('email_verified',1)->latest()->first(); 
        if (isset($exists)) { 
            $User = User::where('email', '=', $result['email'])->where('status',1)->where('email_verified',1)->latest()->first();
            $id = $User->id;
            $password = Str::random(10);

            $updatepsw = User::where('id', $id)->where('status',1)->where('email_verified',1)->latest()->update(array(
                'password' => Hash::make($password),
            ));

            $logo = '';
            $url_link = \URL::to("/");
            $url = $url_link . '/';
            $email = $User->email;
            // $email = 'svapnil.p@mailinator.com';
            $password = $password;
            $name = $User->name;

            $ismail = $this->attachment_email($email, $password, $name, $url, $logo);

            return back()->with('success', 'Check Your email and get new password.');
        } else {
            $user = User::where('email', '=', $result['email'])->latest()->first();
            if($user->status == 2){
                return back()->with('error', 'your account is deleted.');
            }elseif($user->email_verified == 0){
                return back()->with('error', 'Email is not verified.');
            }else{
                return back()->with('error', 'Please enter valid email address.');
            }
            
        }
    }

    public function attachment_email($email, $password, $name, $url, $logo)
    { 
        $setting = Setting::find(1); 
        $from_email = $setting['email'];
        $from_name = urldecode($setting['from_name']); 
        $data = array('email' => $email, 'password' => $password, 'name' => $name, 'url' => $url, 'id' => '18', 'logo' => $logo, 'from_email' => $from_email,'from_name' => $from_name);
        Mail::send('password', $data, function ($message) use ($data) { 
            $message->to($data['email'], $data['from_name'])->subject('Password has been reset succesfully!'); 
            $message->from($data['from_email'], $data['from_name']);
        });
    }

    public function logoutMainUser()
    {
        Auth::logout(); 
        Session::flush();
        return redirect()->route('admin.login');
    }

    public function validateRequest()
    {
        $validateData = request()->validate([
            'email' => 'required', 
        ]);
        return $validateData;
    }
}
