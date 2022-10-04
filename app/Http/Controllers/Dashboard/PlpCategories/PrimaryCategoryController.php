<?php
 
namespace App\Http\Controllers\Dashboard\PlpCategories;
use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Role;
use App\Models\PrimaryCategory;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\ExampleProduct;
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
use App\Exports\PlpCategories\PrimaryCategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class PrimaryCategoryController extends Controller
{
    private $uploadPath = "uploads/primary_categories/";

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
        $categories = PrimaryCategory::where('status','!=',2)->count();  
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),1);
        return view("dashboard.plp_master_data.plp_categories.primary_category.list", compact("categories",'allowed_permissions')); 
    } 

    
    public function anyData(Request $request) 
    {  
        $sort='primary_categories.created_at';  
        $sortBy='DESC';  
        $totalAr = PrimaryCategory::orderBy($sort,$sortBy);   
        $totalRecords = $totalAr->where('status','!=',2)->get()->count();

        $totalAr = $totalAr->get();  
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {   
            $RoleEdit =  route('primary.categories.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('primary.categories.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('primary.categories.delete',['id'=>base64_encode($data->id)]);
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
                 isset($data->category_name) ? mb_strimwidth($data->category_name, 0, 30, "...") : '',
                 isset($data->url_key) ? mb_strimwidth($data->url_key, 0, 30, "...") : '',
                 isset($data->position) ? mb_strimwidth($data->position, 0, 30, "...") : '',
                 $status, 
                 $options, 
            ); 

        }  

        return response()->json($data_arr);
    }

    public function create()
    {    
        return view('dashboard.plp_master_data.plp_categories.primary_category.create');
    }

    
    public function store(Request $request)
    {   
         
        $this->validate($request, [
            'category_name' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',
            'url_key' => 'required|max:50',
            'position' => 'required|integer|unique:primary_categories,position,NULL,id,status,0|unique:primary_categories,position,NULL,id,status,1',
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
                $uploadPath = public_path()."/uploads/primary_categories/"; 
                $request->file($formFileName)->move($uploadPath, $fileFinalName_ar);
            } 
            $icon = $fileFinalName_ar; 
        }

        $PrimaryCategory = new PrimaryCategory();
        $PrimaryCategory->category_name = $request->category_name;
        $PrimaryCategory->url_key = $request->url_key; 
        $PrimaryCategory->position = $request->position;
        $PrimaryCategory->meta_title = $request->meta_title;  
        $PrimaryCategory->meta_description = $request->meta_description;
        $PrimaryCategory->description = $request->description; 
        $PrimaryCategory->position = $request->position;
        $PrimaryCategory->meta_title = $request->meta_title; 
        $PrimaryCategory->icon = $icon;
        $PrimaryCategory->status = 1;
        $PrimaryCategory->created_at = date('Y-m-d H:i:s');
        $PrimaryCategory->updated_at = date('Y-m-d H:i:s'); 
        $PrimaryCategory->save();   
        
        if(isset($request->is_seo) && $request->is_seo == 1){
            $Seo = new Seo();
            $Seo->primary_category_id = $PrimaryCategory->id;
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
        

        return redirect()->route('primary.categories')->with('success', 'Primary Category Created successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id); 

        $category = PrimaryCategory::where('id',$id)->where('status','!=',2)->first();  
        $seo = Seo::where('primary_category_id',$id)->first(); 
        return view('dashboard.plp_master_data.plp_categories.primary_category.edit', compact('encode_id','category','seo'));
    } 

    public function update(Request $request)
    {  
        $id = base64_decode($request->encode_id);  
        if(isset($request->is_seo) && $request->is_seo == 1){ 
            $seo = Seo::where('primary_category_id',$id)->count();
            if($seo > 0){
                Seo::where('primary_category_id',$id)->update([
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
                $Seo->primary_category_id = $id;
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
            $category = PrimaryCategory::find($id);
            $this->validate($request, [
                'category_name' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',
                'url_key' => 'required|max:50',
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
                    'position' => 'required|integer|unique:primary_categories,position,NULL,id,status,0|unique:primary_categories,position,NULL,id,status,1',
                ]);
            }
            PrimaryCategory::where('id',$id)->update([
                'category_name' => $request->category_name,
                'url_key' => $request->url_key,
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
                    $uploadPath = public_path()."/uploads/primary_categories/"; 
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
                PrimaryCategory::where('id',$id)->update([ 
                    'icon' => $icon, 
                ]);
            }
        } 
        return redirect()->route('primary.categories')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {  
        $id = base64_decode($encode_id);   
        $category = PrimaryCategory::where('id',$id)->where('status','!=',2)->first();  
        $seo = Seo::where('primary_category_id',$id)->first();  
        return view('dashboard.plp_master_data.plp_categories.primary_category.show', compact('category','seo'));
    }

    public function destroy(Request $request)
    {    
        $subcategories = SubCategory::where('status','!=',2)->where('primary_category_id',$request->id)->count();
        $product_categories = ProductCategory::where('status','!=',2)->where('primary_category_id',$request->id)->count(); 
        $example_products = ExampleProduct::where('status','!=',2)->where('primary_category_id',$request->id)->count(); 
        if($subcategories == 0 && $product_categories == 0 && $example_products == 0){
            PrimaryCategory::where('id',$request->id)->update(['status' => 2]); 
            return 1;  
        }else{
            return 2;
        } 
    }

    public function export() 
    {
        $excel = Excel::download(new PrimaryCategoriesExport, 'primary_categories.xlsx');  
        Session::forget('data'); 
        return $excel;
    }

    public function BulkAction(Request $request) 
    {
        $bulk_ids = explode (",", $request->selected_ids);  
        if(isset($bulk_ids) && $bulk_ids[0] != ''){
            if($request->action == 'active'){
                foreach ($bulk_ids as $key => $value) { 
                    PrimaryCategory::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    PrimaryCategory::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) { 
                    $subcategories = SubCategory::where('status','!=',2)->where('primary_category_id',$value)->count();
                    $product_categories = ProductCategory::where('status','!=',2)->where('primary_category_id',$value)->count(); 
                    $example_products = ExampleProduct::where('status','!=',2)->where('primary_category_id',$value)->count(); 
                    if($subcategories == 0 && $product_categories == 0 && $example_products == 0){
                        PrimaryCategory::where('id',$value)->update(['status' => 2]); 
                        $error[] = null;  
                    }else{
                        $error[] = 1;
                    }  
                }
                if(count($error) > 0){
                    $message = "Some categories are assigned to subcategories, product categories and example products so you can't delete that.";
                }else{
                    $message = 'Data deleted successfully.';
                }
                
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('primary.categories')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 
 
}
