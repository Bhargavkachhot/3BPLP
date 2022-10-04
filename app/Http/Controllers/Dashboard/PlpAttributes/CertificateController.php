<?php
 
namespace App\Http\Controllers\Dashboard\PlpAttributes;
use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Role; 
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\Certificate;
use App\Models\CertificateProductCategory;
use App\Models\CertificateSubcategory; 
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
use App\Exports\PlpAttributes\CertificateExport;
use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\ImportCertificate; 
use Session;

class CertificateController extends Controller
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
        $certificates = Certificate::where('status','!=',2)->count();  
 
        return view("dashboard.plp_master_data.plp_attributes.certificates.list", compact("certificates",'allowed_permissions'));
    } 
    
    public function anyData(Request $request) 
    {  


        $sort='certificates.created_at';  
        $sortBy='DESC';     
        $totalAr = Certificate::with('certificate_subcategories.subcategory')->orderBy($sort,$sortBy);   
        $totalRecords = $totalAr->where('status','!=',2)->get()->count(); 
        $totalAr = $totalAr->get();    
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {   
            if(isset($data['primary_category']->category_name)){
                $primary_category = $data['primary_category']->category_name;
            }else{
                $primary_category = '';
            } 
            if(isset($data['subcategory']->subcategory)){
                $subcategory = $data['subcategory']->subcategory;
            }else{
                $subcategory = '';
            }

            $RoleEdit =  route('certificates.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('certificates.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('certificates.delete',['id'=>base64_encode($data->id)]);
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
            
            $certificate_subcategories = Helper::GetCertificateSubcategories($data->id); 
            $certificates_categories = Helper::GetCertificateProductCategories($data->id);  

            $data_arr['data'][] =array(   
                isset($data->id) ? $data->id: '', 
                isset($data->certificate) ? mb_strimwidth($data->certificate, 0, 30, "..."): '',
                isset($data->description) ? mb_strimwidth($data->description, 0, 30, "..."): '', 
                isset($certificate_subcategories) ? mb_strimwidth(implode(', ', $certificate_subcategories), 0, 30, "..."): '', 
                isset($certificates_categories) ? mb_strimwidth(implode(', ', $certificates_categories), 0, 30, "...") : '', 
                isset($data->position) ? $data->position : '', 
                $status, 
                $options, 
            );  
        }  

        return response()->json($data_arr);  
              
    }


    public function create()
    {      
        $product_subcategories = SubCategory::where('status',1)->select('id','subcategory','artical_number')->get();
        $product_categories = ProductCategory::where('status',1)->select('id','product_category','artical_number')->get(); 

        if(count($product_subcategories) > 0){
            foreach ($product_subcategories as $key => $value) { 
                $all_product_subcategories[$key]['id'] = $value->id;
                $all_product_subcategories[$key]['text'] = $value->artical_number.' '.$value->subcategory; 
            } 
        }else{
            $all_product_subcategories = [];
        }

        if(count($product_categories) > 0){
            foreach ($product_categories as $key => $value) { 
                $all_product_categories[$key]['id'] = $value->id;
                $all_product_categories[$key]['text'] = $value->artical_number.' '.$value->product_category; 
            } 
        }else{
            $all_product_categories = [];
        } 
        return view('dashboard.plp_master_data.plp_attributes.certificates.create',compact('all_product_categories','all_product_subcategories'));
    }

    
    public function store(Request $request)
    {    
        $this->validate($request, [ 
            'certificate' => 'required|max:50',
            'description' => 'required|max:1000',  
            'product_subcategories' => 'required', 
            'product_categories' => 'required', 
            'position' => 'required|integer|min:1|unique:certificates,position,NULL,id,status,0|unique:certificates,position,NULL,id,status,1', 
        ]);  

 
        $Certificate = new Certificate();
        $Certificate->certificate = $request->certificate;
        $Certificate->description = $request->description;
        $Certificate->position = $request->position;  
        $Certificate->status = 1; 
        $Certificate->created_at = date('Y-m-d H:i:s');
        $Certificate->updated_at = date('Y-m-d H:i:s'); 
        $Certificate->save();

        if(isset($request->product_subcategories) && count($request->product_subcategories) > 0){
            foreach ($request->product_subcategories as $key => $value) {
                $CertificateSubcategory = new CertificateSubcategory();
                $CertificateSubcategory->certificate_id = $Certificate->id;
                $CertificateSubcategory->subcategory_id = $value; 
                $CertificateSubcategory->created_at = date('Y-m-d H:i:s');
                $CertificateSubcategory->updated_at = date('Y-m-d H:i:s'); 
                $CertificateSubcategory->save();
            } 
        }

        if(isset($request->product_categories) && count($request->product_categories) > 0){
            foreach ($request->product_categories as $key => $value) {
                $CertificateProductCategory = new CertificateProductCategory();
                $CertificateProductCategory->certificate_id = $Certificate->id;
                $CertificateProductCategory->product_category_id = $value; 
                $CertificateProductCategory->created_at = date('Y-m-d H:i:s');
                $CertificateProductCategory->updated_at = date('Y-m-d H:i:s'); 
                $CertificateProductCategory->save(); 
            } 
        } 

        return redirect()->route('certificates')->with('success', 'Certificatecreated successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $certificate = Certificate::where('id',$id)->where('status','!=',2)->first();
        $CertificateSubcategory = CertificateSubcategory::with('subcategory')->where('certificate_id',$id)->get();  
        $CertificateProductCategory = CertificateProductCategory::with('productcategory')->where('certificate_id',$id)->get();   
        $product_subcategories = SubCategory::where('status',1)->select('id','subcategory','artical_number')->get();
        $product_categories = ProductCategory::where('status',1)->select('id','product_category','artical_number')->get(); 

        if(count($product_subcategories) > 0){
            foreach ($product_subcategories as $key => $value) { 
                $all_product_subcategories[$key]['id'] = $value->id;
                $all_product_subcategories[$key]['text'] = $value->artical_number.' '.$value->subcategory; 
            } 
        }else{
            $all_product_subcategories = [];
        }

        if(count($product_categories) > 0){
            foreach ($product_categories as $key => $value) { 
                $all_product_categories[$key]['id'] = $value->id;
                $all_product_categories[$key]['text'] = $value->artical_number.' '.$value->product_category; 
            } 
        }else{
            $all_product_categories = [];
        }    
        return view('dashboard.plp_master_data.plp_attributes.certificates.edit', compact('encode_id','certificate','CertificateProductCategory','CertificateSubcategory','all_product_subcategories','all_product_categories'));
    } 

    public function update(Request $request)
    {   
        $id = base64_decode($request->encode_id); 

        $certificates = Certificate::where('status','!=',2)->find($id);

        $this->validate($request, [  
            'certificate' => 'required|max:50',
            'description' => 'required|max:1000',     
            'product_subcategories' => 'required', 
            'product_categories' => 'required',  
            'status' => 'required',  
        ]); 

        if ($request->position != $certificates->position) {
            $this->validate($request, [
                'position' => 'required|integer|min:1|unique:certificates,position,NULL,id,status,0|unique:certificates,position,NULL,id,status,1',
            ]);
        } 

        Certificate::where('id',$id)->where('status','!=',2)->update([
            'certificate' => $request->certificate, 
            'description' => $request->description, 
            'position' => $request->position, 
            'status' => $request->status,
        ]); 

        $previously_selected_subcategories = CertificateSubcategory::where('certificate_id',$id)->pluck('subcategory_id')->toArray();  

        sort($previously_selected_subcategories); 

        if (count($request->product_subcategories) > 0 && $previously_selected_subcategories != $request->product_subcategories){ 

            CertificateSubcategory::where('certificate_id',$id)->delete();

            foreach ($request->product_subcategories as $key => $value) {

                $CertificateSubcategory = new CertificateSubcategory();
                $CertificateSubcategory->certificate_id = $id;
                $CertificateSubcategory->subcategory_id = $value; 
                $CertificateSubcategory->created_at = date('Y-m-d H:i:s');
                $CertificateSubcategory->updated_at = date('Y-m-d H:i:s'); 
                $CertificateSubcategory->save();

            }

        } 
        $previously_selected_product_categories = CertificateProductCategory::where('certificate_id',$id)->pluck('product_category_id')->toArray();  

        sort($previously_selected_product_categories); 

        if (count($request->product_categories) > 0 && $previously_selected_product_categories != $request->product_categories){ 
            
            CertificateProductCategory::where('certificate_id',$id)->delete();

            foreach ($request->product_categories as $key => $value) {

                $CertificateProductCategory = new CertificateProductCategory();
                $CertificateProductCategory->certificate_id = $id;
                $CertificateProductCategory->product_category_id = $value; 
                $CertificateProductCategory->created_at = date('Y-m-d H:i:s');
                $CertificateProductCategory->updated_at = date('Y-m-d H:i:s'); 
                $CertificateProductCategory->save();

            }

        }  
        return redirect()->route('certificates')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $certificate = Certificate::where('id',$id)->where('status','!=',2)->first();
        $CertificateSubcategory = CertificateSubcategory::with('subcategory')->where('certificate_id',$id)->get();  
        $CertificateProductCategory = CertificateProductCategory::with('productcategory')->where('certificate_id',$id)->get();   
        $product_subcategories = SubCategory::where('status',1)->select('id','subcategory','artical_number')->get();
        $product_categories = ProductCategory::where('status',1)->select('id','product_category','artical_number')->get(); 

        if(count($product_subcategories) > 0){
            foreach ($product_subcategories as $key => $value) { 
                $all_product_subcategories[$key]['id'] = $value->id;
                $all_product_subcategories[$key]['text'] = $value->artical_number.' '.$value->subcategory; 
            } 
        }else{
            $all_product_subcategories = [];
        }

        if(count($product_categories) > 0){
            foreach ($product_categories as $key => $value) { 
                $all_product_categories[$key]['id'] = $value->id;
                $all_product_categories[$key]['text'] = $value->artical_number.' '.$value->product_category; 
            } 
        }else{
            $all_product_categories = [];
        }    
        return view('dashboard.plp_master_data.plp_attributes.certificates.show', compact('encode_id','certificate','CertificateProductCategory','CertificateSubcategory','all_product_subcategories','all_product_categories'));   
    }

    public function destroy(Request $request)
    {    
        Certificate::where('id',$request->id)->where('status','!=',2)->update([
            'status' => 2,  
        ]); 
        return 1; 
    }

    public function export() 
    {
        $excel = Excel::download(new CertificateExport, 'certificates.xlsx');  
        Session::forget('data'); 
        return $excel;
    } 

    public function import() 
    {
        return view("dashboard.plp_master_data.plp_attributes.certificates.import");
    }  

    public function importStore(Request $request){  
        if($request->file('file') == null){
            return redirect()->back()->with('error', 'File not selected.'); 
        }else{
            Excel::import(new ImportCertificate,$request->file('file')->store('files'));  
            return redirect()->route('certificates'); 
        }
        
    }
  
    
    public function ProductCategoryTags(Request $request) 
    { 
        if(isset($request->product_category_id) && count($request->product_category_id) > 0){
            $requested_product_category_id = $request->product_category_id;
        }else{
            $requested_product_category_id = [];
        }
        $product_subcategories = SubCategory::where('status',1)->pluck('id')->toArray();  
        $selected_subcategories =  ProductCategory::whereIn('id',$requested_product_category_id)->where('status',1)->groupBy('subcategory_id')->pluck('subcategory_id')->toArray();   
        sort($selected_subcategories);
        sort($product_subcategories);  

        if ($selected_subcategories == $product_subcategories){
            return true; 
        }else{ 
            $selected_product_subcategories = SubCategory::whereIn('id',$selected_subcategories)->where('status',1)->pluck('id')->toArray();

            $all_product_subcategories = SubCategory::where('status',1)->select('id','subcategory','artical_number')->get();   
            if(count($all_product_subcategories) > 0){
                $all_product_subcategories_html = '<select class="tag_selection1" multiple="multiple" name="product_categories[]">';
                    $subcategories_to_show = [];
                    foreach ($all_product_subcategories as $key => $value) { 
                        if(in_array($value->id, $selected_product_subcategories)){
                            $subcategories_to_show[] = $value->id;
                        }    
                    }  
                    foreach ($all_product_subcategories as $key => $value) { 
                       if(in_array($value->id, $subcategories_to_show)){
                            $all_product_subcategories_html .= '<option selected value="'.$value->id.'">'.$value->artical_number.' '.$value->subcategory.'</option>';
                       }else{
                            $all_product_subcategories_html .= '<option value="'.$value->id.'">'.$value->artical_number.' '.$value->subcategory.'</option>';
                       } 
                       
                    }
                $all_product_subcategories_html .= '</select>'; 
            }else{
                $all_product_subcategories_html = null;
            }     
            return $all_product_subcategories_html;
        } 

    }

    public function SubcategoryTags(Request $request) 
    { 
        if(isset($request->subcategory_id) && count($request->subcategory_id) > 0){
            $requested_subcategory_id = $request->subcategory_id;
        }else{
            $requested_subcategory_id = [];
        }
        $selected_subcategories = SubCategory::whereIn('id',$requested_subcategory_id)->where('status',1)->pluck('id')->toArray();     
        $all_product_categories = ProductCategory::whereIn('subcategory_id',$selected_subcategories)->where('status',1)->select('id','product_category','artical_number')->get();  
        if(count($all_product_categories) > 0){
            $all_product_categories_html = '<select class="tag_selection2" multiple="multiple" name="product_categories[]">'; 
                foreach ($all_product_categories as $key => $value) {  
                    $all_product_categories_html .= '<option selected value="'.$value->id.'">'.$value->artical_number.' '.$value->product_category.'</option>'; 
                }
            $all_product_categories_html .= '</select>'; 
        }else{
            $all_product_categories_html = null;
        }     
        return $all_product_categories_html; 
    }    


    public function BulkAction(Request $request) 
    {
        //dd($request->selected_ids);
        $bulk_ids = explode (",", $request->selected_ids);  
        if(isset($bulk_ids) && count($bulk_ids) > 0){
            if($request->action == 'active'){
                foreach ($bulk_ids as $key => $value) { 
                    Certificate::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    Certificate::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) {     
                    Certificate::where('id',$value)->update(['status' => 2]); 
                }
                $message = 'Data deleted successfully.';
                
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('certificates')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 
    
    
 
} 
