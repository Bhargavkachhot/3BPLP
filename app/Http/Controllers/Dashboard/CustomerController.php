<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\FrontUser; 
use App\Models\ProductCategory;
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

class CustomerController extends Controller
{
    private $uploadPath = "uploads/front_user/";

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
        $front_users = FrontUser::where('user_role',1)->where('status','!=',2)->count();  
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),7); 
        return view("dashboard.customers.list", compact("front_users","allowed_permissions")); 
    } 

    public function anyData(Request $request) 
    {
         
        $sort='user_front.created_at';  
        $sortBy='DESC';  
        $totalAr = FrontUser::where('user_role',1)->orderBy($sort,$sortBy);   
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
            $images .= '<img  src="'. asset('uploads/front_users/'.$data->image).'" class="" style="height: 100px;width: 100px;" title="'.$data->image.'"> </a> ';

            $allowed_permissions = Helper::GetRolePermission(Auth::id(),7);

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
        $product_categories = ProductCategory::where('status',1)->select('id','product_category')->get();
        return view("dashboard.customers.create", compact('roles','product_categories')); 
    }

    
    public function Store(Request $request)
    {    
        // $this->validate($request, [
        //     'name' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/', 
        //     'phone_number' => 'required|digits:10|unique:user_front,phone_number,NULL,id,status,1|unique:user_front,phone_number,NULL,id,status,0',
        //     'email'=>'required|max:50|email|unique:user_front,email,NULL,id,status,1|unique:user_front,email,NULL,id,status,0', 
        //     'company_size' => 'required', 
        //     'company_address' => 'required|max:200|regex:/(^[-0-9A-Za-z.,\/ ]+$)/',
        //     'zip' => 'required|regex:/\b\d{5}\b/', 
        //     'country' => 'required|max:50|regex:/^[a-zA-Z ]+$/',
        //     'profile_picture' => 'required|mimes:png,jpeg,jpg',
        //     'vat_number' => 'required|max:50|regex:/^[a-zA-Z0-9]+$/',
        //     'product_category' => 'required'
        // ]);  


        // if($request->profile_picture != null){ 
        //     $formFileName = "profile_picture";
        //     $fileFinalName_ar = "";
        //     if ($request->$formFileName != "") {
        //         $fileFinalName_ar = time() . rand(1111,
        //                 9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
        //         $uploadPath = public_path()."/uploads/front_users/"; 
        //         $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
        //     }  
        // }else{
        //     $fileFinalName_ar = null;
        // }

        // $FrontUser = new FrontUser();
        // $FrontUser->name = $request->name;
        // $FrontUser->phone_number = $request->phone_number;
        // $FrontUser->email = $request->email; 
        // $FrontUser->company_size = $request->company_size;  
        // $FrontUser->company_address = $request->company_address;  
        // $FrontUser->zip = $request->zip;  
        // $FrontUser->country = $request->country; 
        // $FrontUser->profile_picture = $fileFinalName_ar;
        // $FrontUser->vat_number = $request->vat_number;  
        // $FrontUser->product_category = $request->product_category;  
        // $FrontUser->password = bcrypt($request->password); 
        // $FrontUser->status = 1; 
        // $FrontUser->email_verified = 0;  
        // $FrontUser->created_at = date('Y-m-d H:i:s');
        // $FrontUser->updated_at = date('Y-m-d H:i:s'); 
        // $FrontUser->save(); 

        // $id = $FrontUser->id;  

        // $url_link = \URL::to("/");
        // $url = $url_link . '/';
        // $email = $User->email; 
        // $logo = $url.'assets/frontend/logo/logo.svg';
        // $password = $request->password; 
        // $name = $User->name;
        // $action = 'store';
        // $verify_email_url = $url.'verify-email/'.base64_encode($User->id); 

        $url_link = \URL::to("/");
        $url = $url_link . '/';
        $email = 'bhargav.k@vrinsoft.com'; 
        $logo = $url.'assets/frontend/logo/logo.svg';
        $password = "password"; 
        $name = 'name'; 
        $action = 'store';
        $verify_email_url = $url.'verify-email/'.base64_encode(1); 

        $ismail = $this->attachment_email($email, $password, $name, $url, $logo, $action, $verify_email_url);
        
        return redirect()->route('customers')->with('success', 'Data Stored successfully.'); 
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
        $front_users = FrontUser::where('user_role',1)->where('status','!=',2)->find($id); 

        if (!empty($front_users)) {
            $roles = Role::where('status',1)->get(); 
            $selected_roles = UserRole::where('user_id',$id)->pluck('role_id')->toarray();  
            return view("dashboard.customers.show", compact("front_users",'roles','encode_id','selected_roles'));
        } else {
            return redirect()->action('Dashboard\UserController@index');
        }
    }

    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id);   
        $front_users = FrontUser::where('user_role',1)->where('status','!=',2)->find($id); 

        if (!empty($front_users)) {
            $roles = Role::where('status',1)->get(); 
            $selected_roles = UserRole::where('user_id',$id)->pluck('role_id')->toarray();  
            return view("dashboard.customers.edit", compact("front_users",'roles','encode_id','selected_roles'));
        } else {
            return redirect()->action('Dashboard\UserController@index');
        }
    }

    public function update(Request $request)
    {  
        $id = base64_decode($request->encode_id);  
        $user = FrontUser::where('user_role',1)->where('status','!=',2)->find($id); 
        if (!empty($user)) { 

            $this->validate($request, [
                'name' => 'required|max:255|string|regex:/^[a-zA-ZÑñ\s]+$/',
            ]);

            if ($request->email != $user->email) {
                $this->validate($request, [
                    'email' => 'required|email|unique:user_front,email,NULL,id,status,1|unique:user_front,email,NULL,id,status,0', 
                ]);
            }
            if ($request->mobile_number != $user->mobile_number) {
                $this->validate($request, [ 
                    'mobile_number' => 'required|digits:10|unique:user_front,mobile_number,NULL,id,status,1|unique:user_front,mobile_number,NULL,id,status,0', 
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
                    $uploadPath = public_path()."/uploads/front_users/"; 
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
                FrontUser::where('user_role',1)->where('id',$id)->where('status','!=',2)->update([ 
                    'image' => $image, 
                ]);
            }

            FrontUser::where('user_role',1)->where('id',$id)->where('status','!=',2)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number, 
            ]);

            if($request->password != null){
                FrontUser::where('user_role',1)->where('id',$id)->where('status','!=',2)->update([ 
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
            
            return redirect()->route('front.users')->with('success', 'Data Submitted successfully.'); 

        } else {
            return redirect()->action('Dashboard\UserController@index');
        }
    }

    public function destroy(Request $request)
    {    
        if(Auth::id() != $request->id){
            FrontUser::where('user_role',1)->where('id',$request->id)->where('status','!=',2)->update(['status' => 2]); 
        }   
        return 1; 
    }


    public function export() 
    { 
        $excel = Excel::download(new FrontUsersExport, 'front_users.xlsx'); 
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
                        FrontUser::where('user_role',1)->where('id',$value)->update(['status' => 1]);  
                    }
                }
                $message = 'activated';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    if(Auth::id() != $value){
                        FrontUser::where('user_role',1)->where('id',$value)->update(['status' => 0]); 
                    }
                }
                $message = 'inactivated';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) { 
                    if(Auth::id() != $value){
                        FrontUser::where('user_role',1)->where('id',$value)->update(['status' => 2]); 
                    }
                }    
                $message = 'deleted';
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('front.users')->with('success', 'Data '.$message.' successfully.');
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 

}
