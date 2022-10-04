<?php
 
namespace App\Http\Controllers\Dashboard\PlpCategories;
use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Role;
use App\Models\PrimaryCategory;
use App\Models\SubCategory; 
use App\Models\ProductCategory;
use App\Models\ExampleProduct;
use App\Models\ProductAttribute;
use App\Models\PrimaryPackaging;
use App\Models\ProductAttributeSubcategory;
use App\Models\PrimaryPackagingSubcategory; 
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
use App\Exports\PlpCategories\SubcategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class SubCategoryController extends Controller
{
    private $uploadPath = "uploads/subcategories/";

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

    public function index()
    {  
        $subcategories = SubCategory::where('status','!=',2)->count();   
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),1);
        return view("dashboard.plp_master_data.plp_categories.subcategories.list", compact("subcategories",'allowed_permissions')); 
    } 

    
    public function anyData(Request $request) 
    {  
        $sort='subcategories.created_at';  
        $sortBy='DESC';  
        $totalAr = SubCategory::orderBy($sort,$sortBy);   
        $totalRecords = $totalAr->where('status','!=',2)->get()->count();

        $totalAr = $totalAr->get();  
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {   
            $primary_category = PrimaryCategory::where('id',$data->primary_category_id)->where('status',1)->pluck('category_name')->first();

            $RoleEdit =  route('subcategories.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('subcategories.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('subcategories.delete',['id'=>base64_encode($data->id)]);
            $options = "";  

            $allowed_permissions = Helper::GetRolePermission(Auth::id(),1); 

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
                 isset($primary_category) ? mb_strimwidth($primary_category, 0, 30, "...") : '',
                 isset($data->artical_number) ? mb_strimwidth($data->artical_number, 0, 30, "..."): '', 
                 isset($data->subcategory) ? mb_strimwidth($data->subcategory, 0, 30, "...") : '', 
                 isset($data->url_key) ? mb_strimwidth($data->url_key, 0, 30, "...") : '', 
                 isset($data->full_url_key) ? mb_strimwidth($data->full_url_key, 0, 30, "...") : '',
                 isset($data->position) ? mb_strimwidth($data->position, 0, 30, "...") : '', 
                 $status, 
                 $options, 
            ); 

        }  

        return response()->json($data_arr);
 
    }

    public function create()
    {    
        $primary_categories = PrimaryCategory::where('status',1)->get();
        return view('dashboard.plp_master_data.plp_categories.subcategories.create',compact('primary_categories'));
    }

    
    public function store(Request $request)
    {     
        $this->validate($request, [
            'primary_category_id' => 'required',
            'artical_number' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',
            'subcategory' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/', 
            'url_key' => 'required|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'full_url_key' => 'required',
            'position' => 'required|integer|unique:subcategories,position,NULL,id,status,0|unique:subcategories,position,NULL,id,status,1', 
            'meta_title' => 'required',
            'meta_description' => 'required',
            'description' => 'required', 
            'icon' => 'required|mimes:png,jpeg,jpg',
        ]);
        if($request->full_url_key == 'null/null'){
            return back()->withInput($request->input())->with('error', 'Primary catagory and Url Key is required.');
        } 
        if($request->icon != null){

            // Start of Upload Files 
            $formFileName = "icon";
            $fileFinalName_ar = "";
            if ($request->$formFileName != "") {
                $fileFinalName_ar = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $uploadPath = public_path()."/uploads/subcategories/"; 
                $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
            } 
            $icon = $fileFinalName_ar; 
        }
 
        $SubCategory = new SubCategory();
        $SubCategory->primary_category_id = $request->primary_category_id;
        $SubCategory->artical_number = $request->artical_number;
        $SubCategory->subcategory = $request->subcategory;
        $SubCategory->full_url_key = $request->full_url_key; 
        $SubCategory->url_key = $request->url_key; 
        $SubCategory->position = $request->position;
        $SubCategory->meta_title = $request->meta_title;  
        $SubCategory->meta_description = $request->meta_description;
        $SubCategory->description = $request->description; 
        $SubCategory->position = $request->position;
        $SubCategory->meta_title = $request->meta_title; 
        $SubCategory->icon = $icon;
        $SubCategory->status = 1;
        $SubCategory->created_at = date('Y-m-d H:i:s');
        $SubCategory->updated_at = date('Y-m-d H:i:s'); 
        $SubCategory->save(); 

        if(isset($request->is_seo) && $request->is_seo == 1){
            $Seo = new Seo();
            $Seo->subcategory_id = $SubCategory->id;
            $Seo->seo_headline_one = $request->seo_headline_one;
            $Seo->seo_description_one = $request->seo_description_one; 
            $Seo->seo_headline_two = $request->seo_headline_two;
            $Seo->seo_description_two = $request->seo_description_two;  
            $Seo->seo_headline_three = $request->seo_headline_three;
            $Seo->seo_description_three = $request->seo_description_three; 
            $Seo->seo_description_other = $request->seo_description_other;
            $Seo->created_at = date('Y-m-d H:i:s');
            $Seo->updated_at = date('Y-m-d H:i:s'); 
            $Seo->save(); 
        }

        return redirect()->route('subcategories')->with('success', 'Primary Category Created successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id);  
        $category = SubCategory::where('id',$id)->where('status','!=',2)->first();  
        $seo = Seo::where('subcategory_id',$id)->first(); 
        $primary_categories = PrimaryCategory::where('status',1)->get(); 
        return view('dashboard.plp_master_data.plp_categories.subcategories.edit', compact('encode_id','category','primary_categories','seo'));
    } 

    public function update(Request $request)
    {  
        $id = base64_decode($request->encode_id); 
        if(isset($request->is_seo) && $request->is_seo == 1){ 
            $seo = Seo::where('subcategory_id',$id)->count();
            if($seo > 0){
                Seo::where('subcategory_id',$id)->update([
                    'seo_headline_one' => $request->seo_headline_one,
                    'seo_description_one' => $request->seo_description_one,
                    'seo_headline_two' => $request->seo_headline_two,
                    'seo_description_two' => $request->seo_description_two,
                    'seo_headline_three' => $request->seo_headline_three,
                    'seo_description_three' => $request->seo_description_three,
                    'seo_description_other' => $request->seo_description_other,
                ]); 
            }else{

                $Seo = new Seo();
                $Seo->subcategory_id = $id;
                $Seo->seo_headline_one = $request->seo_headline_one;
                $Seo->seo_description_one = $request->seo_description_one; 
                $Seo->seo_headline_two = $request->seo_headline_two;
                $Seo->seo_description_two = $request->seo_description_two;  
                $Seo->seo_headline_three = $request->seo_headline_three;
                $Seo->seo_description_three = $request->seo_description_three; 
                $Seo->seo_description_other = $request->seo_description_other;
                $Seo->created_at = date('Y-m-d H:i:s');
                $Seo->updated_at = date('Y-m-d H:i:s'); 
                $Seo->save(); 
                
            }
        } 
        if(!isset($request->is_seo)){
            $category = SubCategory::where('status','!=',2)->find($id);
            $this->validate($request, [ 
                'primary_category_id' => 'required',
                'artical_number' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',
                'subcategory' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/', 
                'url_key' => 'required|max:50|regex:/^[a-zA-Z0-9_]+$/',
                'position' => 'required|integer',
                'meta_title' => 'required',
                'meta_description' => 'required',
                'description' => 'required', 
            ]);
            if($request->icon != null){
                $this->validate($request, [
                    'icon' => 'mimes:png,jpeg,jpg',
                ]); 
            } 
            if (isset($category) && $request->position != $category->position) {
                $this->validate($request, [
                    'position' => 'required|integer|unique:subcategories,position,NULL,id,status,0|unique:subcategories,position,NULL,id,status,1',
                ]);
            }
            $primary_category_url_key = PrimaryCategory::where('id',$request->primary_category_id)->where('status',1)->pluck('url_key')->first();

            SubCategory::where('id',$id)->where('status','!=',2)->update([
                'primary_category_id' => $request->primary_category_id,
                'artical_number' => $request->artical_number, 
                'subcategory' => $request->subcategory,
                'url_key' => $request->url_key,
                'full_url_key' => $primary_category_url_key.'/'.$request->url_key,
                'position' => $request->position,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'description' => $request->description,
                'status' => $request->status,
            ]); 
            

             

            if($request->icon != null){

                // Start of Upload Files 
                $formFileName = "icon";
                $fileFinalName_ar = "";
                if ($request->$formFileName != "") {
                    $fileFinalName_ar = time() . rand(1111,
                            9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                    $uploadPath = public_path()."/uploads/subcategories/"; 
                    $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
                } 
                  
                if ($request->photo_delete == 1) {
                    // Delete a User file
                    if ($category->icon != "") {
                        File::delete($this->getUploadPath() . $category->icon);
                    } 
                    $icon = "";
                }
                if ($fileFinalName_ar != "") {
                    // Delete a User file
                    if ($category->icon != "") {
                        File::delete($this->getUploadPath() . $category->icon);
                    } 
                    $icon = $fileFinalName_ar;
                }  
                SubCategory::where('id',$id)->update([ 
                    'icon' => $icon, 
                ]);
            }
        }
         
        
        
        return redirect()->route('subcategories')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {  
        $id = base64_decode($encode_id);   
        $category = SubCategory::where('id',$id)->where('status','!=',2)->first();  
        $seo = Seo::where('subcategory_id',$id)->first();  
        $primary_categories = PrimaryCategory::where('status',1)->get(); 

        return view('dashboard.plp_master_data.plp_categories.subcategories.show', compact('category','seo','primary_categories'));
    }

    public function destroy(Request $request)
    {    
        $product_categories = ProductCategory::where('status','!=',2)->where('subcategory_id',$request->id)->count();
        $product_attributes_subcategories = ProductAttributeSubcategory::where('product_subcategory_id',$request->id)->count(); 
        $example_products = ExampleProduct::where('status','!=',2)->where('subcategory_id',$request->id)->count();
        if($product_categories == 0 && $product_attributes_subcategories == 0 && $example_products == 0){
            SubCategory::where('id',$request->id)->update(['status' => 2]); 
            return 1;
        }else{ 
            return 2;
        } 
    }

    public function export() 
    {
        $excel = Excel::download(new SubcategoryExport, 'subcategories.xlsx');  
        Session::forget('data'); 
        return $excel;
    }

    public function GetUrlKey(Request $request) 
    { 
        $url_key = PrimaryCategory::where('id',$request->id)->where('status',1)->pluck('url_key')->first();  
        return $url_key;
    } 
    
    public function BulkAction(Request $request) 
    {
        $bulk_ids = explode (",", $request->selected_ids);  
        if(isset($bulk_ids) && $bulk_ids[0] != ''){
            if($request->action == 'active'){
                foreach ($bulk_ids as $key => $value) { 
                    SubCategory::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    SubCategory::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) { 
                    $product_categories = ProductCategory::where('status','!=',2)->where('subcategory_id',$value)->count();
                    $example_products = ExampleProduct::where('status','!=',2)->where('subcategory_id',$value)->count();

                    // $product_attributes_subcategories = ProductAttributeSubcategory::where('product_subcategory_id',$value)->count(); 
                    // $primary_packaging_subcategories = PrimaryPackagingSubcategory::where('product_subcategory_id',$value)->count(); 


                $product_attributes_subcategories = ProductAttribute::join('product_attributes_subcategories','product_attributes.id','product_attributes_subcategories.product_attribute_id') 
                    ->where('product_attributes.status','!=',2)
                    ->where('product_attributes_subcategories.product_subcategory_id',$value)
                    ->pluck('product_attributes_subcategories.product_subcategory_id') 
                    ->toArray(); 

                $primary_packaging_subcategories = PrimaryPackaging::join('primary_packaging_subcategories','primary_packaging.id','primary_packaging_subcategories.primary_packaging_id') 
                    ->where('primary_packaging.status','!=',2)
                    ->where('primary_packaging_subcategories.product_subcategory_id',$value)
                    ->pluck('primary_packaging_subcategories.product_subcategory_id') 
                    ->toArray();      

                    if($product_categories == 0 && $product_attributes_subcategories == 0 && $example_products == 0 && $primary_packaging_subcategories == 0){
                        SubCategory::where('id',$value)->update(['status' => 2]); 
                        $error[] = null;
                    }else{ 
                        $error[] = 1;
                    } 
                }
                if(count($error) > 0){
                    $message = "Some categories are assigned to product categories, example products, product attributes and primary packaging so you can't delete that.";
                }else{
                    $message = 'Data deleted successfully.';
                }
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('subcategories')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 
 
}
