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
use App\Models\ProductAttribute;
use App\Models\PrimaryPackagingCategory;
use App\Models\PrimaryPackagingSubcategory;
use App\Models\PrimaryPackagingAttribute;
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
use App\Exports\PlpAttributes\PrimaryPackagingExport;
use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\ImportPrimaryPackaging; 
use Session;

class PrimaryPackagingController extends Controller
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
        $primary_packaging = PrimaryPackaging::where('status','!=',2)->count();  
 
        return view("dashboard.plp_master_data.plp_attributes.primary_packaging.list", compact("primary_packaging",'allowed_permissions'));
    } 
    
    public function anyData(Request $request) 
    {  


        $sort='primary_packaging.created_at';  
        $sortBy='DESC';    
        $totalAr = PrimaryPackaging::with('primary_packaging_subcategories.subcategory')->orderBy($sort,$sortBy);    
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

            $RoleEdit =  route('primary.packaging.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('primary.packaging.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('primary.packaging.delete',['id'=>base64_encode($data->id)]);
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
            
            $primary_packaging_subcategories = Helper::GetPrimaryPackagingSubcategories($data->id); 
            $primary_packaging_categories = Helper::GetPrimaryPackagingCategories($data->id);  

            $data_arr['data'][] =array(   
                isset($data->id) ? $data->id: '', 
                isset($data->primary_packaging) ? mb_strimwidth($data->primary_packaging, 0, 30, "..."): '', 
                isset($primary_packaging_subcategories) ? mb_strimwidth(implode(', ', $primary_packaging_subcategories), 0, 30, "..."): '', 
                isset($primary_packaging_categories) ? mb_strimwidth(implode(', ', $primary_packaging_categories), 0, 30, "...") : '', 
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
        return view('dashboard.plp_master_data.plp_attributes.primary_packaging.create',compact('all_product_categories','all_product_subcategories'));
    }

    
    public function store(Request $request)
    {    
        $this->validate($request, [ 
            'primary_packaging' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',  
            'product_subcategories' => 'required', 
            'product_categories' => 'required', 
            'position' => 'required|integer|min:1|unique:primary_packaging,position,NULL,id,status,0|unique:primary_packaging,position,NULL,id,status,1', 
        ]);  

 
        $primaryPackaging = new PrimaryPackaging();
        $primaryPackaging->primary_packaging = $request->primary_packaging;
        $primaryPackaging->position = $request->position;  
        $primaryPackaging->status = 1; 
        $primaryPackaging->created_at = date('Y-m-d H:i:s');
        $primaryPackaging->updated_at = date('Y-m-d H:i:s'); 
        $primaryPackaging->save();

        if(isset($request->product_subcategories) && count($request->product_subcategories) > 0){
            foreach ($request->product_subcategories as $key => $value) {
                $primaryPackagingSubcategory = new PrimaryPackagingSubcategory();
                $primaryPackagingSubcategory->primary_packaging_id = $primaryPackaging->id;
                $primaryPackagingSubcategory->product_subcategory_id = $value; 
                $primaryPackagingSubcategory->created_at = date('Y-m-d H:i:s');
                $primaryPackagingSubcategory->updated_at = date('Y-m-d H:i:s'); 
                $primaryPackagingSubcategory->save();
            } 
        }

        if(isset($request->product_categories) && count($request->product_categories) > 0){
            foreach ($request->product_categories as $key => $value) {
                $primaryPackagingCategory = new PrimaryPackagingCategory();
                $primaryPackagingCategory->primary_packaging_id = $primaryPackaging->id;
                $primaryPackagingCategory->product_category_id = $value; 
                $primaryPackagingCategory->created_at = date('Y-m-d H:i:s');
                $primaryPackagingCategory->updated_at = date('Y-m-d H:i:s'); 
                $primaryPackagingCategory->save(); 
            } 
        } 

        return redirect()->route('primary.packaging')->with('success', 'primary packaging created successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $primary_packaging = PrimaryPackaging::where('id',$id)->where('status','!=',2)->first();
        $primaryPackagingSubcategory = PrimaryPackagingSubcategory::with('subcategory')->where('primary_packaging_id',$id)->get();  
        $primaryPackagingCategory = PrimaryPackagingCategory::with('productcategory')->where('primary_packaging_id',$id)->get();   
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
        return view('dashboard.plp_master_data.plp_attributes.primary_packaging.edit', compact('encode_id','primary_packaging','primaryPackagingCategory','primaryPackagingSubcategory','all_product_subcategories','all_product_categories'));
    } 

    public function update(Request $request)
    {   
        $id = base64_decode($request->encode_id); 

        $primary_packaging = PrimaryPackaging::where('status','!=',2)->find($id);

        $this->validate($request, [ 
            'primary_packaging' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',  
            'product_subcategories' => 'required', 
            'product_categories' => 'required',  
            'status' => 'required',  
        ]); 

        if ($request->position != $primary_packaging->position) {
            $this->validate($request, [
                'position' => 'required|integer|min:1|unique:primary_packaging,position,NULL,id,status,0|unique:primary_packaging,position,NULL,id,status,1',
            ]);
        } 

        PrimaryPackaging::where('id',$id)->where('status','!=',2)->update([
            'primary_packaging' => $request->primary_packaging, 
            'position' => $request->position, 
            'status' => $request->status,
        ]); 

        $previously_selected_subcategories = PrimaryPackagingSubcategory::where('primary_packaging_id',$id)->pluck('product_subcategory_id')->toArray();  

        sort($previously_selected_subcategories); 

        if (count($request->product_subcategories) > 0 && $previously_selected_subcategories != $request->product_subcategories){ 

            PrimaryPackagingSubcategory::where('primary_packaging_id',$id)->delete();

            foreach ($request->product_subcategories as $key => $value) {

                $primaryPackagingSubcategory = new PrimaryPackagingSubcategory();
                $primaryPackagingSubcategory->primary_packaging_id = $id;
                $primaryPackagingSubcategory->product_subcategory_id = $value; 
                $primaryPackagingSubcategory->created_at = date('Y-m-d H:i:s');
                $primaryPackagingSubcategory->updated_at = date('Y-m-d H:i:s'); 
                $primaryPackagingSubcategory->save();

            }

        } 
        $previously_selected_product_categories = PrimaryPackagingCategory::where('primary_packaging_id',$id)->pluck('product_category_id')->toArray();  

        sort($previously_selected_product_categories); 

        if (count($request->product_categories) > 0 && $previously_selected_product_categories != $request->product_categories){ 
            
            PrimaryPackagingCategory::where('primary_packaging_id',$id)->delete();

            foreach ($request->product_categories as $key => $value) {

                $primaryPackagingCategory = new PrimaryPackagingCategory();
                $primaryPackagingCategory->primary_packaging_id = $id;
                $primaryPackagingCategory->product_category_id = $value; 
                $primaryPackagingCategory->created_at = date('Y-m-d H:i:s');
                $primaryPackagingCategory->updated_at = date('Y-m-d H:i:s'); 
                $primaryPackagingCategory->save();

            }

        }  
        return redirect()->route('primary.packaging')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $primary_packaging = PrimaryPackaging::where('id',$id)->where('status','!=',2)->first();
        $primaryPackagingSubcategory = PrimaryPackagingSubcategory::with('subcategory')->where('primary_packaging_id',$id)->get();  
        $primaryPackagingCategory = PrimaryPackagingCategory::with('productcategory')->where('primary_packaging_id',$id)->get();   
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
        return view('dashboard.plp_master_data.plp_attributes.primary_packaging.show', compact('encode_id','primary_packaging','primaryPackagingCategory','primaryPackagingSubcategory','all_product_subcategories','all_product_categories'));   
    }

    public function destroy(Request $request)
    {    
        PrimaryPackaging::where('id',$request->id)->where('status','!=',2)->update([
            'status' => 2,  
        ]); 
        return 1; 
    }

    public function export() 
    {
        $excel = Excel::download(new PrimaryPackagingExport, 'primary_packaging.xlsx');  
        Session::forget('data'); 
        return $excel;
    } 

    public function import() 
    {
        return view("dashboard.plp_master_data.plp_attributes.primary_packaging.import");
    }  

    public function importStore(Request $request){  
        $request->validate([
            'file'=> 'required|mimes:xlsx, csv, xls'
         ]);
        if($request->file('file') == null){
            return redirect()->back()->with('error', 'File not selected.'); 
        }else{
            Excel::import(new ImportPrimaryPackaging,$request->file('file')->store('files'));  
            return redirect()->route('primary.packaging'); 
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
                    PrimaryPackaging::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    PrimaryPackaging::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) {  

                    //$primary_packaging_attributes  = PrimaryPackagingAttributeId::where('primary_packaging_id',$value)->count();

                    $primary_packaging_attribute_ids = PrimaryPackagingAttribute::join('primary_packaging_attribute_ids','primary_packaging_attributes.id','primary_packaging_attribute_ids.primary_packaging_attribute_id') 
                    ->where('primary_packaging_attributes.status','!=',2)
                    ->where('primary_packaging_attribute_ids.primary_packaging_id',$value)
                    ->pluck('primary_packaging_attribute_ids.primary_packaging_id') 
                    ->toArray();   

                    if(count($primary_packaging_attribute_ids) == 0){
                        PrimaryPackaging::where('id',$value)->update(['status' => 2]);  
                        $error[] = null;
                    }else{ 
                        $error[] = 1;
                    } 
                }
                if(count($error) > 0){
                    $message = "Some primary packagings are assigned to primary packaging attributes so you can't delete that.";
                }else{
                    $message = 'Data deleted successfully.';
                }
                
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('primary.packaging')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 
    
    
 
} 
