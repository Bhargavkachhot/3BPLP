<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Setting;
use Mail;
use App\Models\Role;
use App\Models\WebmasterSection;
use Auth;
use File;
use Illuminate\Config;
use App\Http\Requests\ChangePasswordRequest;
use Redirect;
use Helper;
use Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Session; 

class UserController extends Controller
{
    private $uploadPath = "uploads/users/";

    // Define Default Variables

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status','!=',2)->count(); 
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),2); 
        return view("dashboard.users.list", compact("users","allowed_permissions")); 
    } 

    public function anyData(Request $request) 
    {
         
        $sort='users.created_at';  
        $sortBy='DESC';  
        $totalAr = User::orderBy($sort,$sortBy);   
        $totalRecords = $totalAr->where('status','!=',2)->get()->count();

        $totalAr = $totalAr->get();  
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {   
            $RoleEdit =  route('user.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('user.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('user.delete',['id'=>base64_encode($data->id)]);
            $options = "";  
            $images = ""; 
            $images .= '<img  src="'. asset('uploads/users/'.$data->image).'" class="" style="height: 100px;width: 100px;" title="'.$data->image.'"> </a> ';

            $allowed_permissions = Helper::GetRolePermission(Auth::id(),2); 

            if(isset($allowed_permissions) && $allowed_permissions->read == 1){
                $options .= '<a class="btn-sm list show-icon" href="'.$RoleShow.'" title="Show"><i class="fa fa-eye" style="color: #00ec00;" aria-hidden="true"></i></a> ';
            }

            if(isset($allowed_permissions) && $allowed_permissions->update == 1){
                $options .= '<a class="btn-sm list edit-icon" href="'.$RoleEdit.'" title="Edit"><i class="fa fa-edit" style="color: #00ec00;" aria-hidden="true"></i></a> '; 
            }

            if(isset($allowed_permissions) && $allowed_permissions->delete == 1 && Auth::id() != $data->id){ 
                $options .= '<a class="btn-sm delete-icon remove-record" href="javascript:void(0)" id="'.$data->id.'" title="Delete"><i class="fa fa-trash" style="color: #00ec00;" aria-hidden="true"></i></a> '; 
            } 
            
            if($data->status == 1){
                $status = '<i class="fa fa-check" aria-hidden="true"></i>';
            }else{
                $status = '<i class="fa fa-times" aria-hidden="true"></i>';
            }
           
            
            if($options == ''){
                $options = "-"; 
            } 

             if($data->email_verified == 1){
                $email_verified = '<i class="fa fa-check" aria-hidden="true"></i>';
            }else{
                $email_verified = '<i class="fa fa-times" aria-hidden="true"></i>';
            } 

            $data_arr['data'][] =array(   
                 isset($data->id) ? $data->id: '',
                 $images, 
                 isset($data->name) ? $data->name: '',
                 isset($data->email) ? $data->email : '',
                 isset($data->mobile_number) ? $data->mobile_number : '',
                 $status,
                 $email_verified, 
                 $options, 
            );   
        }  

        return response()->json($data_arr); 
    }

    public function create()
    {      
        $roles = Role::where('status',1)->get();   
        return view("dashboard.users.create", compact('roles')); 
    }

    
    public function Store(Request $request)
    {   

        $this->validate($request, [
            'name' => 'required|max:255|string|regex:/^[a-zA-ZÑñ\s]+$/', 
            'email'=>'required|email|unique:users,email,NULL,id,status,1|unique:users,email,NULL,id,status,0',
            'mobile_number' => 'required|digits:10|unique:users,mobile_number,NULL,id,status,1|unique:users,mobile_number,NULL,id,status,0',
            'image' => 'mimes:png,jpeg,jpg',
            'password' => 'string|min:8',
            'roles' => 'required',
        ]);  


        if($request->image != null){

            // Start of Upload Files 
            $formFileName = "image";
            $fileFinalName_ar = "";
            if ($request->$formFileName != "") {
                $fileFinalName_ar = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $uploadPath = public_path()."/uploads/users/"; 
                $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
            }  
        }else{
            $fileFinalName_ar = null;
        }

        $User = new User();
        $User->name = $request->name;
        $User->email = $request->email; 
        $User->mobile_number = $request->mobile_number;
        $User->password = bcrypt($request->password); 
        $User->image = $fileFinalName_ar;
        $User->status = 1; 
        $User->email_verified = 0; 
        $User->is_accepted_tou = 0;  
        $User->created_at = date('Y-m-d H:i:s');
        $User->updated_at = date('Y-m-d H:i:s'); 
        $User->save(); 

        $id = $User->id;
        if(isset($request->roles)){  
            $UserRole = new UserRole();
            $UserRole->user_id = $id;
            $UserRole->role_id = $request->roles; 
            $UserRole->created_at = date('Y-m-d H:i:s');
            $UserRole->updated_at = date('Y-m-d H:i:s'); 
            $UserRole->save(); 
        }  

        
        $url_link = \URL::to("/");
        $url = $url_link . '/';
        $email = $User->email; 
        $logo = $url.'assets/frontend/logo/logo.svg';
        $password = $request->password; 
        $name = $User->name;
        $action = 'store';
        $verify_email_url = $url.'verify-email/'.base64_encode($User->id); 

        // $url_link = \URL::to("/");
        // $url = $url_link . '/';
        // $email = 'bhargav.k@vrinsoft.com'; 
        // $logo = $url.'assets/frontend/logo/logo.svg';
        // $password = "password"; 
        // $name = 'name'; 
        // $action = 'store';
        // $verify_email_url = $url.'verify-email/'.base64_encode(1); 

        $ismail = $this->attachment_email($email, $password, $name, $url, $logo, $action, $verify_email_url);
        
        return redirect()->route('users')->with('success', 'Data Stored successfully.'); 
    }

    public function attachment_email($email, $password, $name, $url, $logo, $action, $verify_email_url)
    { 
        $setting = Setting::find(1); 
        $from_email = $setting['email'];
        $from_name = urldecode($setting['from_name']); 
        if($action == 'store'){ 
            $subject = 'You registered succesfully. Please verify email.';
            $data = array('email' => $email, 'password' => $password, 'name' => $name, 'url' => $url, 'id' => '20', 'logo' => $logo, 'from_email' => $from_email,'from_name' => $from_name,'subject' => $subject ,'verify_email_url' => $verify_email_url); 
            Mail::send('password', $data, function ($message) use ($data) { 
                $message->to($data['email'], $data['from_name'])->subject($data['subject']); 
                $message->from($data['from_email'], $data['from_name']);
            });
        }else{
            $subject = 'Your password updated.';
            $data = array('email' => $email, 'password' => $password, 'name' => $name, 'url' => $url, 'id' => '19', 'logo' => $logo, 'from_email' => $from_email,'from_name' => $from_name,'subject' => $subject); 
            Mail::send('password', $data, function ($message) use ($data) { 
                $message->to($data['email'], $data['from_name'])->subject($data['subject']); 
                $message->from($data['from_email'], $data['from_name']);
            });
        } 
        
    }

    public function show($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $users = User::where('status','!=',2)->find($id); 

        if (!empty($users)) {
            $roles = Role::where('status',1)->get(); 
            $selected_roles = UserRole::where('user_id',$id)->pluck('role_id')->toarray();  
            return view("dashboard.users.show", compact("users",'roles','encode_id','selected_roles'));
        } else {
            return redirect()->action('Dashboard\UserController@index');
        }
    }

    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id);   
        $users = User::where('status','!=',2)->find($id); 

        if (!empty($users)) {
            $roles = Role::where('status',1)->get(); 
            $selected_roles = UserRole::where('user_id',$id)->pluck('role_id')->toarray();  
            return view("dashboard.users.edit", compact("users",'roles','encode_id','selected_roles'));
        } else {
            return redirect()->action('Dashboard\UserController@index');
        }
    }

    public function update(Request $request)
    {  
        $id = base64_decode($request->encode_id);  
        $user = User::where('status','!=',2)->find($id); 
        if (!empty($user)) { 

            $this->validate($request, [
                'name' => 'required|max:255|string|regex:/^[a-zA-ZÑñ\s]+$/',
            ]);

            if ($request->email != $user->email) {
                $this->validate($request, [
                    'email' => 'required|email|unique:users,email,NULL,id,status,1|unique:users,email,NULL,id,status,0', 
                ]);
            }
            if ($request->mobile_number != $user->mobile_number) {
                $this->validate($request, [ 
                    'mobile_number' => 'required|digits:10|unique:users,mobile_number,NULL,id,status,1|unique:users,mobile_number,NULL,id,status,0', 
                ]);
            }

            if($request->image != null){
               $this->validate($request, [
                    'image' => 'mimes:png,jpeg,jpg',
                ]); 
            }

            if($request->password != null){
               $this->validate($request, [
                    'password' => 'string|min:8',
                ]); 
            } 
            if($request->image != null){

                // Start of Upload Files 
                $formFileName = "image";
                $fileFinalName_ar = "";
                if ($request->$formFileName != "") {
                    $fileFinalName_ar = time() . rand(1111,
                            9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                    $uploadPath = public_path()."/uploads/users/"; 
                    $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
                } 
                  
                if ($request->photo_delete == 1) {
                    // Delete a User file
                    if ($user->image != "") {
                        File::delete($this->getUploadPath() . $user->image);
                    } 
                    $image = "";
                }
                if ($fileFinalName_ar != "") {
                    // Delete a User file
                    if ($user->image != "") {
                        File::delete($this->getUploadPath() . $user->image);
                    } 
                    $image = $fileFinalName_ar;
                }  
                User::where('id',$id)->where('status','!=',2)->update([ 
                    'image' => $image, 
                ]);
            }

            User::where('id',$id)->where('status','!=',2)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number, 
            ]);

            if($request->password != null){
                User::where('id',$id)->where('status','!=',2)->update([ 
                    'password' => bcrypt($request->password),
                ]);

                $url_link = \URL::to("/");
                $url = $url_link . '/';
                $email = $request->email; 
                $logo = $url.'assets/frontend/logo/logo.svg';
                $password = $request->password; 
                $name = $request->name; 
                $action = 'update';
                $verify_email_url = null;
                $ismail = $this->attachment_email($email, $request->password, $name, $url, $logo, $action, $verify_email_url);
                
            }
            
            UserRole::where('user_id',$id)->update(['role_id' => $request->roles]); 
            
            return redirect()->route('users')->with('success', 'Data Submitted successfully.'); 

        } else {
            return redirect()->action('Dashboard\UserController@index');
        }
    }

    public function destroy(Request $request)
    {    
        if(Auth::id() != $request->id){
            User::where('id',$request->id)->where('status','!=',2)->update(['status' => 2]); 
        }   
        return 1; 
    }


    public function export() 
    { 
        $excel = Excel::download(new UsersExport, 'users.xlsx'); 
        Session::forget('data'); 
        return $excel;
    }

    public function BulkAction(Request $request) 
    {
        $bulk_ids = explode (",", $request->selected_ids);  
        if(isset($bulk_ids) && $bulk_ids[0] != ''){
            if($request->action == 'active'){
                foreach ($bulk_ids as $key => $value) { 
                    if(Auth::id() != $value){
                        User::where('id',$value)->update(['status' => 1]);  
                    }
                }
                $message = 'activated';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    if(Auth::id() != $value){
                        User::where('id',$value)->update(['status' => 0]); 
                    }
                }
                $message = 'inactivated';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) { 
                    if(Auth::id() != $value){
                        User::where('id',$value)->update(['status' => 2]); 
                    }
                }    
                $message = 'deleted';
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('users')->with('success', 'Data '.$message.' successfully.');
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 

}
