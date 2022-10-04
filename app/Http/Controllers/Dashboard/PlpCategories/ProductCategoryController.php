<?php
 
namespace App\Http\Controllers\Dashboard\PlpCategories;
use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Role;
use App\Models\ProductCategory;
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
use App\Models\ExampleProduct;
use App\Models\ProductAttribute;
use App\Models\PrimaryPackaging;
use App\Models\ProductAttributeCategory;
use App\Models\RoleModulePermission;
use App\Models\PrimaryPackagingCategory;
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
use App\Exports\PlpCategories\ProductCategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ProductCategoryController extends Controller
{
    private $uploadPath = "uploads/product_categories/";

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
        $categories = ProductCategory::where('status','!=',2)->count();  
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),1);  
        return view("dashboard.plp_master_data.plp_categories.product_category.list", compact("categories",'allowed_permissions')); 
    } 

    
    public function anyData(Request $request) 
    {  
        $sort='product_categories.created_at';  
        $sortBy='DESC';    
        $totalAr = ProductCategory::with('primary_category','subcategory')->orderBy($sort,$sortBy);    
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

            $RoleEdit =  route('product.categories.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('product.categories.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('product.categories.delete',['id'=>base64_encode($data->id)]);
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
                mb_strimwidth($primary_category, 0, 30, "..."),
                mb_strimwidth($subcategory, 0, 30, "..."),
                isset($data->artical_number) ? mb_strimwidth($data->artical_number, 0, 30, "..."): '', 
                isset($data->product_category) ? mb_strimwidth($data->product_category, 0, 30, "..."): '',
                // isset($data->url_key) ? strlen($data->url_key) > 15 ? substr($data->url_key,0,15)."..." : $data->url_key : '',
                // isset($data->full_url_key) ? strlen($data->full_url_key) > 15 ? substr($data->full_url_key,0,15)."..." : $data->full_url_key : '',
                isset($data->position) ? $data->position : '', 
                $status, 
                $options, 
            );  
        }  

        return response()->json($data_arr); 
    }

    public function create()
    {    
        $primary_categories = PrimaryCategory::where('status',1)->pluck('id')->toArray(); 
        if(isset($primary_categories)){
            $subcategories = SubCategory::select('id','subcategory','artical_number')->whereIn('primary_category_id',$primary_categories)->where('status',1)->get();
        }else{
            $subcategories = [];
        } 
        return view('dashboard.plp_master_data.plp_categories.product_category.create',compact('primary_categories','subcategories'));
    }

    
    public function store(Request $request)
    {   
        $this->validate($request, [
            'primary_category_id' => 'required',
            'subcategory_id' => 'required',
            'artical_number' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',
            'product_category' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',  
            'url_key' => 'required|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'position' => 'required|integer|unique:product_categories,position,NULL,id,status,0|unique:product_categories,position,NULL,id,status,1', 
            'meta_title' => 'required',
            'meta_description' => 'required',
            'description' => 'required', 
            'icon' => 'required|mimes:png,jpeg,jpg',
        ]);  
        if($request->icon != null){

            // Start of Upload Files 
            $formFileName = "icon";
            $fileFinalName_ar = "";
            if ($request->$formFileName != "") {
                $fileFinalName_ar = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $uploadPath = public_path()."/uploads/product_categories/"; 
                $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
            } 
            $icon = $fileFinalName_ar; 
        }

        $primary_category_url_key = PrimaryCategory::where('id',$request->primary_category_id)->where('status',1)->pluck('url_key')->first();

        $subcategory_url_key = SubCategory::where('id',$request->subcategory_id)->where('status',1)->pluck('url_key')->first();

        $ProductCategory = new ProductCategory();
        $ProductCategory->primary_category_id = $request->primary_category_id;
        $ProductCategory->subcategory_id = $request->subcategory_id;
        $ProductCategory->artical_number = $request->artical_number;
        $ProductCategory->product_category = $request->product_category;
        $ProductCategory->url_key = $request->url_key; 
        $ProductCategory->full_url_key = $primary_category_url_key.'/'.$subcategory_url_key.'/'.$request->url_key;
        $ProductCategory->position = $request->position; 
        $ProductCategory->meta_title = $request->meta_title;  
        $ProductCategory->meta_description = $request->meta_description;
        $ProductCategory->description = $request->description; 
        $ProductCategory->position = $request->position;
        $ProductCategory->meta_title = $request->meta_title; 
        $ProductCategory->icon = $icon;
        $ProductCategory->status = 1;
        $ProductCategory->created_at = date('Y-m-d H:i:s');
        $ProductCategory->updated_at = date('Y-m-d H:i:s'); 
        $ProductCategory->save();  

        if(isset($request->is_seo) && $request->is_seo == 1){
            $Seo = new Seo();
            $Seo->product_category_id = $ProductCategory->id;
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

        return redirect()->route('product.categories')->with('success', 'Product Category Created successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id); 
        $category = ProductCategory::where('id',$id)->where('status','!=',2)->first();  
        $seo = Seo::where('product_category_id',$id)->first(); 
        $primary_categories = PrimaryCategory::where('status',1)->select('id','category_name')->get();  
        $primary_categories_array = PrimaryCategory::where('status',1)->pluck('id')->toArray();  
        if(isset($primary_categories)){
            $subcategories = SubCategory::select('id','subcategory','artical_number')->whereIn('primary_category_id',$primary_categories_array)->where('status',1)->get();
        }else{
            $subcategories = [];
        } 

        return view('dashboard.plp_master_data.plp_categories.product_category.edit', compact('encode_id','category','seo','primary_categories','subcategories'));
    } 

    public function update(Request $request)
    {  
        $id = base64_decode($request->encode_id);
        
        if(!isset($request->is_seo)){ 
            $category = ProductCategory::where('status','!=',2)->find($id);
             
            $this->validate($request, [
                'primary_category_id' => 'required',
                'subcategory_id' => 'required',
                'artical_number' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',
                'product_category' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/', 
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
                    'position' => 'required|integer|unique:product_categories,position,NULL,id,status,0|unique:product_categories,position,NULL,id,status,1',
                ]);
            }

            $primary_category_url_key = PrimaryCategory::where('id',$request->primary_category_id)->where('status',1)->pluck('url_key')->first();

            $subcategory_url_key = SubCategory::where('id',$request->subcategory_id)->where('status',1)->pluck('url_key')->first();
     
            ProductCategory::where('id',$id)->where('status','!=',2)->update([
                'primary_category_id' => $request->primary_category_id,
                'subcategory_id' => $request->subcategory_id,
                'artical_number' => $request->artical_number, 
                'product_category' => $request->product_category, 
                'url_key' => $request->url_key, 
                'full_url_key' => $primary_category_url_key.'/'.$subcategory_url_key.'/'.$request->url_key,
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
                    $uploadPath = public_path()."/uploads/product_categories/"; 
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
                ProductCategory::where('id',$id)->where('status','!=',2)->update([ 
                    'icon' => $icon, 
                ]);
            }
        }

        if(isset($request->is_seo) && $request->is_seo == 1){ 
            $seo = Seo::where('product_category_id',$id)->count();
            if($seo > 0){
                Seo::where('product_category_id',$id)->update([
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
                $Seo->product_category_id = $id;
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
        return redirect()->route('product.categories')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {   
        $id = base64_decode($encode_id);  
        $category = ProductCategory::where('id',$id)->where('status','!=',2)->first();  
        $seo = Seo::where('product_category_id',$id)->first(); 
        $primary_categories = PrimaryCategory::where('status',1)->select('id','category_name')->get(); 
        $subcategories = SubCategory::select('id','subcategory','artical_number')->where('primary_category_id',$category->primary_category_id)->where('status',1)->get();   
        return view('dashboard.plp_master_data.plp_categories.product_category.show', compact('category','seo','primary_categories','subcategories'));
    }

    public function destroy(Request $request)
    {    
        $product_attributes_categories = ProductAttributeCategory::where('product_category_id',$request->id)->count(); 
        $example_products = ExampleProduct::where('status','!=',2)->where('product_category_id',$request->id)->count();
        if($product_attributes_categories == 0 && $example_products == 0){
            ProductCategory::where('id',$request->id)->update(['status' => 2]); 
            return 1;
        }else{ 
            return 2;
        } 
    }

    public function export() 
    { 
        $excel = Excel::download(new ProductCategoriesExport, 'product_categories.xlsx');  
        Session::forget('data'); 
        return $excel;
    }

    public function GetUrlKey(Request $request) 
    { 
        $url_key = PrimaryCategory::where('id',$request->id)->where('status',1)->pluck('url_key')->first();  
        return $url_key;
    }

    public function GetSubCategoryUrlKey(Request $request) 
    { 
        $url_key = SubCategory::where('id',$request->id)->where('status',1)->pluck('url_key')->first();   
        return $url_key;
    }

    public function GetPrimaryCategory(Request $request) 
    { 
        $primary_category_id = SubCategory::where('id',$request->id)->where('status',1)->pluck('primary_category_id')->first();  
        $primary_category = PrimaryCategory::where('id',$primary_category_id)->where('status',1)->select('category_name','id')->get();  

        $html = ""; 
        if(count($primary_category) > 0){
             foreach ($primary_category as $key => $value) {
                $html .= "<option value=".$value->id." selected >".$value->category_name."</option>";
             }  
             $id = $primary_category[0]->id;
        }else{
            $html .= "<option disabled selected>No primary category found.</option>"; 
            $id = 0;
        } 
        $response = [
                "html"  => $html,
                "id"    => $id
            ];
        $json=json_encode($response,JSON_FORCE_OBJECT);  
        return $json; 
        
    }

    public function BulkAction(Request $request) 
    {
        $bulk_ids = explode (",", $request->selected_ids);    
        if(isset($bulk_ids) && $bulk_ids[0] != ''){
            if($request->action == 'active'){
                foreach ($bulk_ids as $key => $value) { 
                    ProductCategory::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    ProductCategory::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) {  
                    $example_products = ExampleProduct::where('status','!=',2)->where('product_category_id',$value)->count(); 
                    // $product_attributes_categories = ProductAttributeCategory::where('product_category_id',$value)->count();

                    // $primary_packaging_categories  = PrimaryPackagingCategory::where('product_category_id',$value)->count();


                    $product_attributes_categories = ProductAttribute::join('product_attributes_categories','product_attributes.id','product_attributes_categories.product_attribute_id') 
                    ->where('product_attributes.status','!=',2)
                    ->where('product_attributes_categories.product_category_id',$value)
                    ->pluck('product_attributes_categories.product_category_id') 
                    ->toArray(); 

                    $primary_packaging_categories = PrimaryPackaging::join('primary_packaging_categories','primary_packaging.id','primary_packaging_categories.primary_packaging_id') 
                    ->where('primary_packaging.status','!=',2)
                    ->where('primary_packaging_categories.product_category_id',$value)
                    ->pluck('primary_packaging_categories.product_category_id') 
                    ->toArray();  


                    if($product_attributes_categories == 0 && $example_products == 0 && $primary_packaging_categories == 0 ){
                        ProductCategory::where('id',$value)->update(['status' => 2]); 
                        $error[] = null;
                    }else{ 
                        $error[] = 1;
                    } 
                }
                if(count($error) > 0){
                    $message = "Some categories are assigned to example products, product attributes and primary packaging so you can't delete that.";
                }else{
                    $message = 'Data deleted successfully.';
                }
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('product.categories')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    }
 
}
