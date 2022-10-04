<?php
 
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Role;
use App\Models\FAQ; 
use App\Models\RoleModulePermission; 
use App\Models\WebmasterSetting;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use File;
use Helper;
use Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 
use Session;

class FaqController extends Controller
{ 

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

    public function index()
    {  
        $categories = FAQ::where('status','!=',2)->count();  
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),6);  
        return view("dashboard.faq.list", compact("categories",'allowed_permissions')); 
    } 

    
    public function anyData(Request $request) 
    {  
        $sort='faqs.created_at';  
        $sortBy='DESC';  
        $totalAr = FAQ::orderBy($sort,$sortBy);   
        $totalRecords = $totalAr->where('status','!=',2)->get()->count();

        $totalAr = $totalAr->get();  
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {    
            $RoleEdit =  route('faq.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('faq.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('faq.delete',['id'=>base64_encode($data->id)]);
            $options = "";  

            $allowed_permissions = Helper::GetRolePermission(Auth::id(),6); 

            if(isset($allowed_permissions) && $allowed_permissions->read == 1){
                $options .= '<a class="btn-sm list show-icon" href="'.$RoleShow.'" title="Show"><i class="fa fa-eye" style="color: #00ec00;" aria-hidden="true"></i></a> ';
            }

            if(isset($allowed_permissions) && $allowed_permissions->update == 1){
                $options .= '<a class="btn-sm list edit-icon" href="'.$RoleEdit.'" title="Edit"><i class="fa fa-edit" style="color: #00ec00;" aria-hidden="true"></i></a> '; 
            }

            if(isset($allowed_permissions) && $allowed_permissions->delete == 1){ 
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

            
            $data_arr['data'][] =array(   
                 isset($data->id) ? $data->id: '',
                 isset($data->question) ? mb_strimwidth($data->question, 0, 30, "...") : '',
                 isset($data->answer) ? mb_strimwidth( $data->answer, 0, 30, "...") : '',
                 isset($data->position) ? mb_strimwidth($data->position, 0, 30, "...") : '',
                 $status, 
                 $options, 
            ); 

        }  

        return response()->json($data_arr);
    }

    public function create()
    {    
        return view('dashboard.faq.create');
    }

    
    public function store(Request $request)
    {   
         
        $this->validate($request, [
            'question' => 'required|max:255', 
            'position' => 'required|integer|unique:faqs,position,NULL,id,status,0|unique:faqs,position,NULL,id,status,1',
            'answer' => 'required', 
        ]); 
 

        $faq = new faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer; 
        $faq->position = $request->position;  
        $faq->status = 1;
        $faq->created_at = date('Y-m-d H:i:s');
        $faq->updated_at = date('Y-m-d H:i:s'); 
        $faq->save();    

        return redirect()->route('faq')->with('success', 'Faq Created successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $faq = FAQ::where('id',$id)->where('status','!=',2)->first();  
        return view('dashboard.faq.edit', compact('encode_id','faq'));
    } 

    public function update(Request $request)
    {  

        $id = base64_decode($request->encode_id);  
        $faq = FAQ::find($id);
        $this->validate($request, [
            'question' => 'required|max:255',
            'answer' => 'required', 
        ]);  
        if (isset($faq) && $request->position != $faq->position) {
            $this->validate($request, [
                'position' => 'required|integer|unique:faqs,position,NULL,id,status,0|unique:faqs,position,NULL,id,status,1',
            ]);
        } 
        FAQ::where('id',$id)->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'position' => $request->position, 
            'status' => $request->status,
        ]);  

        return redirect()->route('faq')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {  
        $id = base64_decode($encode_id);   
        $faq = FAQ::where('id',$id)->where('status','!=',2)->first();  
        return view('dashboard.faq.show', compact('faq'));
    }

    public function destroy(Request $request)
    { 
        FAQ::where('id',$request->id)->update(['status' => 2]); 
        return 1;   
    } 

    public function BulkAction(Request $request) 
    {
        $bulk_ids = explode (",", $request->selected_ids);  
        if(isset($bulk_ids) && $bulk_ids[0] != ''){
            if($request->action == 'active'){
                foreach ($bulk_ids as $key => $value) { 
                    FAQ::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    FAQ::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) {   
                        FAQ::where('id',$value)->update(['status' => 2]);  
                } 
                $message = 'Data deleted successfully.'; 
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('faq')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 
 
}
