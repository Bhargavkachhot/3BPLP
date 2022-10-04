<?php

namespace App\Http\Controllers\Dashboard; 
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Carbon\Carbon;
use DB;  
use App\Models\User; 
use Auth;
use Hash;
use File;
use Helper;
use Session;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $uploadPath = "uploads/users/";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth'); 
        if (Auth::user() != null) {
            if(Auth::user()->status != 1 || Auth::user()->email_verified != 1){
                Auth::logout(); 
                Session::flush();
                return redirect()->route('admin.login');
            }   
        }
    }

    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {  
        return view('dashboard.home'); 
    }

    public function edit($id)
    {  
        if (@Auth::user()->permissionsGroup->view_status) {
            $user = User::where('created_by', '=', Auth::user()->id)->orwhere('id', '=', Auth::user()->id)->find($id);
        } else {
            $user = User::find($id);
        } 
        if (!empty($user)) {
            return view("dashboard.admin.edit", compact("user"));
        } else {
            return redirect()->action('Dashboard\UsersController@index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $User = User::find($id); 
        if (!empty($User)) {


            // $this->validate($request, [
            //     'image' => 'mimes:png,jpeg,jpg',
            //     'name' => 'required|max:30',
            // ]);

            if ($request->email != $User->email) {
                $this->validate($request, [
                    'email' => 'required|email|unique:users',
                ]);
            }

            // Start of Upload Files
            $formFileName = "image";
            $fileFinalName_ar = ""; 
            if ($request->$formFileName != "") { 
                $fileFinalName_ar = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $uploadPath = public_path()."/uploads/users/"; 

                //$path = $this->getUploadPath();

                $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
            }


            // echo "No images";
            // exit();
            // End of Upload Files

            //if ($id != 1) {
                $User->name = $request->name;
                $User->email = $request->email;
                if ($request->password != "") {
                    $User->password = bcrypt($request->password);
                }
            //}
            if ($request->image_delete == 1) {
                // Delete a User file
                if ($User->image != "") {
                    File::delete($this->getUploadPath() . $User->image);
                }

                $User->image = "";
            }
            if ($fileFinalName_ar != "") {
                // Delete a User file
                if ($User->image != "") {
                    File::delete($this->getUploadPath() . $User->image);
                }

                $User->image = $fileFinalName_ar;
            }

            // $User->connect_email = $request->connect_email;
            // if ($request->connect_password != "") {
            //     $User->connect_password = $request->connect_password;
            // } 
            
            $User->save();
            return redirect()->action('Dashboard\DashboardController@edit', $id)->with('doneMessage', __('backend.saveDone'));
        } else {
            return redirect()->action('Dashboard\DashboardController@index');
        }
    }

    public function changePassword(Request $request)
    {   
        return view("dashboard.admin.change_password");
    }

    //update password
    public function updatePassword(Request $request)
    { 
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirmation');
        


        $this->validate($request, [
            'current_password_1' => 'required', 
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ],
        [
            'password.confirmed' => 'The new-password and confirm new-password field does not match.'
        ]
        ); 
        if (!(Hash::check($request->get('current_password_1'), Auth::user()->password)))
        {
            // echo "string";exit();
        // The passwords matches
            // dd(5);
        return redirect()->back()->with("errorMessage","Your current password does not match. Please try again.");
        }

        if(strcmp($request->get('current_password_1'), $request->get('password')) == 0)
        {
        //Current password and new password are same
        return redirect()->back()->with("errorMessage","New Password cannot be same as your current password. Please choose a different password.");
        }
        if ($password != $password_confirm)
        {
           return redirect()->back()->with("errorMessage","Password do not match with comfirm password.");
        }
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('password'));
        $user->save();
        return redirect()->back()->with("doneMessage","Password changed successfully !");
    } 
}
