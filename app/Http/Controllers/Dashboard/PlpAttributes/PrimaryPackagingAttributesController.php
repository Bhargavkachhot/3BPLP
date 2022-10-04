<?php
 
namespace App\Http\Controllers\Dashboard\PlpAttributes;
use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Role; 
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
use App\Models\ProductCategory; 
use App\Models\PrimaryPackaging;
use App\Models\PrimaryPackagingAttribute;
use App\Models\PrimaryPackagingAttributeId;
use App\Models\ProductAttribute; 
use App\Models\RoleModulePermission;
use App\Models\Seo;
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
use App\Exports\PlpAttributes\PrimaryPackagingAttributeExport;
use Maatwebsite\Excel\Facades\Excel;  
use Session;

class PrimaryPackagingAttributesController extends Controller
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
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),4); 
        $primary_packaging = PrimaryPackagingAttribute::where('status','!=',2)->count();  
 
        return view("dashboard.plp_master_data.plp_attributes.primary_packaging_attributes.list", compact("primary_packaging",'allowed_permissions'));
    } 
    
    public function anyData(Request $request) 
    {  


        $sort='primary_packaging_attributes.created_at';  
        $sortBy='DESC';    
        $totalAr = PrimaryPackagingAttribute::with('primary_packaging_attribute_ids.primary_packaging')->orderBy($sort,$sortBy);    
        $totalRecords = $totalAr->where('status','!=',2)->get()->count(); 
        $totalAr = $totalAr->get();  
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {    
            $primary_packaging = [];
            foreach ($data['primary_packaging_attribute_ids'] as $key => $value) {
                if(isset($value['primary_packaging']) && $value['primary_packaging'] != null){
                    $primary_packaging[] = $value['primary_packaging']->primary_packaging;
                } 
            } 
            $RoleEdit =  route('primary.packaging.attributes.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('primary.packaging.attributes.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('primary.packaging.attributes.delete',['id'=>base64_encode($data->id)]);
            $options = "";  

            $allowed_permissions = Helper::GetRolePermission(Auth::id(),4); 

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
                isset($data->primary_packaging_attribute) ? mb_strimwidth($data->primary_packaging_attribute, 0, 30, "..."): '', 
                isset($primary_packaging) ? mb_strimwidth(implode(', ', $primary_packaging), 0, 30, "..."): '',  
                isset($data->position) ? $data->position : '', 
                $status, 
                $options, 
            );  
        }  

        return response()->json($data_arr);  
              
    }


    public function create()
    {      
        $primary_packaging = PrimaryPackaging::where('status',1)->get();   
        if(count($primary_packaging) > 0){
            foreach ($primary_packaging as $key => $value) { 
                $all_primary_packaging[$key]['id'] = $value->id;
                $all_primary_packaging[$key]['text'] = $value->primary_packaging; 
            } 
        }else{
            $all_primary_packaging = [];
        }

        return view('dashboard.plp_master_data.plp_attributes.primary_packaging_attributes.create',compact('all_primary_packaging'));
    }

    
    public function store(Request $request)
    {    
        $this->validate($request, [ 
            'primary_packaging_attribute' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',  
            'primary_packaging' => 'required',  
            'position' => 'required|integer|min:1|unique:primary_packaging_attributes,position,NULL,id,status,0|unique:primary_packaging_attributes,position,NULL,id,status,1', 

           

        ]);  

 
        $primaryPackagingAttribute = new PrimaryPackagingAttribute();
        $primaryPackagingAttribute->primary_packaging_attribute = $request->primary_packaging_attribute;
        $primaryPackagingAttribute->position = $request->position;  
        $primaryPackagingAttribute->status = 1; 
        $primaryPackagingAttribute->created_at = date('Y-m-d H:i:s');
        $primaryPackagingAttribute->updated_at = date('Y-m-d H:i:s'); 
        $primaryPackagingAttribute->save(); 

        if(isset($request->primary_packaging) && count($request->primary_packaging) > 0){
            foreach ($request->primary_packaging as $key => $value) {
                $primaryPackagingAttributeSubcategory = new PrimaryPackagingAttributeId();
                $primaryPackagingAttributeSubcategory->primary_packaging_attribute_id = $primaryPackagingAttribute->id;
                $primaryPackagingAttributeSubcategory->primary_packaging_id = $value; 
                $primaryPackagingAttributeSubcategory->created_at = date('Y-m-d H:i:s');
                $primaryPackagingAttributeSubcategory->updated_at = date('Y-m-d H:i:s'); 
                $primaryPackagingAttributeSubcategory->save();
            } 
        } 

        return redirect()->route('primary.packaging.attributes')->with('success', 'Primary packaging attribute created successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $primary_packaging_attribute = PrimaryPackagingAttribute::where('id',$id)->where('status','!=',2)->first();
        $selected_primary_packaging = PrimaryPackagingAttributeId::where('primary_packaging_attribute_id',$id)->pluck('primary_packaging_id')->toArray();  
        $primary_packaging = PrimaryPackaging::where('status',1)->get();   
        if(count($primary_packaging) > 0){
            foreach ($primary_packaging as $key => $value) { 
                $all_primary_packaging[$key]['id'] = $value->id;
                $all_primary_packaging[$key]['text'] = $value->primary_packaging; 
            } 
        }else{
            $all_primary_packaging = [];
        }

        return view('dashboard.plp_master_data.plp_attributes.primary_packaging_attributes.edit', compact('encode_id','primary_packaging','primary_packaging_attribute','all_primary_packaging','selected_primary_packaging'));
    } 

    public function update(Request $request)
    {   
        $id = base64_decode($request->encode_id);  

        $primary_packaging_attribute = PrimaryPackagingAttribute::where('status','!=',2)->find($id);

        $this->validate($request, [ 
            'primary_packaging_attribute' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',  
            'primary_packaging' => 'required',  
            'status' => 'required',  
        ]); 

        if ($request->position != $primary_packaging_attribute->position) {
            $this->validate($request, [
                'position' => 'required|integer|min:1|unique:primary_packaging_attributes,position,NULL,id,status,0|unique:primary_packaging_attributes,position,NULL,id,status,1',
            ]);
        } 

        PrimaryPackagingAttribute::where('id',$id)->where('status','!=',2)->update([
            'primary_packaging_attribute' => $request->primary_packaging_attribute, 
            'position' => $request->position, 
            'status' => $request->status,
        ]); 

        $previously_selected_primary_packaging = PrimaryPackagingAttributeId::where('primary_packaging_attribute_id',$id)->pluck('primary_packaging_id')->toArray();  

        sort($previously_selected_primary_packaging); 


        if (count($request->primary_packaging) > 0 && $previously_selected_primary_packaging != $request->primary_packaging){  
            PrimaryPackagingAttributeId::where('primary_packaging_attribute_id',$id)->delete(); 
            foreach ($request->primary_packaging as $key => $value) { 
                $primaryPackagingAttributeSubcategory = new PrimaryPackagingAttributeId();
                $primaryPackagingAttributeSubcategory->primary_packaging_attribute_id = $id;
                $primaryPackagingAttributeSubcategory->primary_packaging_id = $value; 
                $primaryPackagingAttributeSubcategory->created_at = date('Y-m-d H:i:s');
                $primaryPackagingAttributeSubcategory->updated_at = date('Y-m-d H:i:s'); 
                $primaryPackagingAttributeSubcategory->save(); 
            } 
        }  
        return redirect()->route('primary.packaging.attributes')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $primary_packaging_attribute = PrimaryPackagingAttribute::where('id',$id)->where('status','!=',2)->first();
        $selected_primary_packaging = PrimaryPackagingAttributeId::where('primary_packaging_attribute_id',$id)->pluck('primary_packaging_id')->toArray();  
        $primary_packaging = PrimaryPackaging::where('status',1)->get();   
        if(count($primary_packaging) > 0){
            foreach ($primary_packaging as $key => $value) { 
                $all_primary_packaging[$key]['id'] = $value->id;
                $all_primary_packaging[$key]['text'] = $value->primary_packaging; 
            } 
        }else{
            $all_primary_packaging = [];
        }

        return view('dashboard.plp_master_data.plp_attributes.primary_packaging_attributes.show', compact('encode_id','primary_packaging','primary_packaging_attribute','all_primary_packaging','selected_primary_packaging'));
    }

    public function destroy(Request $request)
    {    
        PrimaryPackagingAttribute::where('id',$request->id)->where('status','!=',2)->update([
            'status' => 2,  
        ]); 
        return 1; 
    }

    public function export() 
    {
        $excel = Excel::download(new PrimaryPackagingAttributeExport, 'primary_packaging_attributes.xlsx');  
        Session::forget('data'); 
        return $excel;
    }   


    public function BulkAction(Request $request) 
    { 
        //dd($request->selected_ids);
        $bulk_ids = explode (",", $request->selected_ids);  
        if(isset($bulk_ids) && count($bulk_ids) > 0){
            if($request->action == 'active'){
                foreach ($bulk_ids as $key => $value) { 
                    PrimaryPackagingAttribute::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    PrimaryPackagingAttribute::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) { 
                    PrimaryPackagingAttribute::where('id',$value)->update(['status' => 2]);  
                    $error = []; 
                }
                if(count($error) > 0){
                    $message = "Some primary packaging attributes are assigned to child attributes.";
                }else{
                    $message = 'Data deleted successfully.';
                }
                
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('primary.packaging.attributes')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 
    
    
 
} 
