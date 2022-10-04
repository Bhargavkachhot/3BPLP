<?php
 
namespace App\Http\Controllers\Dashboard\PlpCategories;
use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Role;
use App\Models\ProductCategory; 
use App\Models\ExampleProduct;
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
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
use App\Exports\PlpCategories\ExampleProductExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ExampleProductController extends Controller
{
    private $uploadPath = "uploads/example_products/";

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
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),1);
        return view("dashboard.plp_master_data.plp_categories.example_product.list", compact('allowed_permissions')); 
    } 

    
    public function anyData(Request $request) 
    {  

        $sort='example_products.created_at';  
        $sortBy='DESC';  
        $totalAr = ExampleProduct::with('productcategory')->orderBy($sort,$sortBy);   
        $totalRecords = $totalAr->where('status','!=',2)->get()->count();

        $totalAr = $totalAr->get();  
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {   
             if(isset($data['productcategory']->product_category)){
                $product_category = $data['productcategory']->artical_number.' '.$data['productcategory']->product_category;
            }else{
                $product_category = '';
            }  

            $RoleEdit =  route('example.products.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('example.products.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('example.products.delete',['id'=>base64_encode($data->id)]);
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
                 $product_category,
                 isset($data->example_product) ? mb_strimwidth($data->example_product, 0, 30, "...") : '',
                 isset($data->position) ? $data->position : '',
                 $status, 
                 $options, 
            ); 

        }   
        return response()->json($data_arr);   
    }

    public function create()
    {   
        $product_categories = ProductCategory::where('status',1)->select('id','product_category')->get(); 
        return view('dashboard.plp_master_data.plp_categories.example_product.create', compact('product_categories'));
    }

    
    public function store(Request $request)
    {    
        $this->validate($request, [
            'primary_category_id' => 'required',
            'subcategory_id' => 'required',
            'product_category_id' => 'required',  
            'example_product' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',
            'position' => 'required|integer|unique:example_products,position,NULL,id,status,0|unique:example_products,position,NULL,id,status,1',  
        ]);  

        $ExampleProduct = new ExampleProduct();
        $ExampleProduct->primary_category_id = $request->primary_category_id;
        $ExampleProduct->subcategory_id = $request->subcategory_id;
        $ExampleProduct->product_category_id = $request->product_category_id;
        $ExampleProduct->example_product = $request->example_product; 
        $ExampleProduct->position = $request->position; 
        $ExampleProduct->status = 1; 
        $ExampleProduct->created_at = date('Y-m-d H:i:s');
        $ExampleProduct->updated_at = date('Y-m-d H:i:s'); 
        $ExampleProduct->save();    

        return redirect()->route('example.products')->with('success', 'Example Product Created successfully.'); 
    }

    
    public function edit($encode_id)
    {  
        $id = base64_decode($encode_id); 
        $example_product = ExampleProduct::where('id',$id)->where('status','!=',2)->first();   
        $primary_categories = PrimaryCategory::where('status',1)->select('id','category_name')->get(); 
        $subcategories = SubCategory::select('id','subcategory','artical_number')->where('primary_category_id',$example_product->primary_category_id)->where('status',1)->get();   
        $product_categories = ProductCategory::where('status',1)->select('id','product_category')->get(); 

        return view('dashboard.plp_master_data.plp_categories.example_product.edit', compact('encode_id','example_product','primary_categories','subcategories','product_categories'));
    } 

    public function update(Request $request)
    {  
        $id = base64_decode($request->encode_id);
        $product = ExampleProduct::find($id);  

        $this->validate($request, [
            'primary_category_id' => 'required',
            'subcategory_id' => 'required',
            'product_category_id' => 'required', 
            'example_product' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/',  
            'position' => 'required|integer',  
        ]);   
        if (isset($product) && $request->position != $product->position) {
            $this->validate($request, [
                'position' => 'required|integer|unique:example_products,position,NULL,id,status,0|unique:example_products,position,NULL,id,status,1',
            ]);
        }
        ExampleProduct::where('id',$id)->where('status','!=',2)->update([
            'primary_category_id' => $request->primary_category_id,
            'subcategory_id' => $request->subcategory_id,
            'product_category_id' => $request->product_category_id, 
            'example_product' => $request->example_product,  
            'position' => $request->position,
            'status' => $request->status,  
        ]);  
        return redirect()->route('example.products')->with('success', 'Data Updated successfully.'); 
    }

    public function show($encode_id)
    {   
        $id = base64_decode($encode_id); 
        $example_product = ExampleProduct::where('id',$id)->where('status','!=',2)->first();   
        $primary_categories = PrimaryCategory::where('status',1)->select('id','category_name')->get(); 
        $subcategories = SubCategory::select('id','subcategory','artical_number')->where('primary_category_id',$example_product->primary_category_id)->where('status',1)->get();  
        $product_categories = ProductCategory::where('status',1)->select('id','product_category','artical_number')->where('primary_category_id',$example_product->primary_category_id)->where('subcategory_id',$example_product->subcategory_id)->get();  

        return view('dashboard.plp_master_data.plp_categories.example_product.show', compact('example_product','primary_categories','subcategories','product_categories'));
    }

    public function destroy(Request $request)
    {    
        ExampleProduct::where('id',$request->id)->where('status','!=',2)->delete();  
        return 1; 
    }

    public function export() 
    { 
        $excel = Excel::download(new ExampleProductExport, 'example_products.xlsx');  
        Session::forget('data'); 
        return $excel;
    } 

    public function GetRelationalData(Request $request) 
    { 
        $product_category = ProductCategory::where('id',$request->id)->where('status',1)->first();   
        $primary_category = PrimaryCategory::where('id',$product_category->primary_category_id)->where('status',1)->select('category_name','id')->get();
        $subcategory = SubCategory::where('id',$product_category->subcategory_id)->where('status',1)->select('subcategory','id')->get();  

        $primary_category_html = ""; 
        $subcategory_html = ""; 

        if(count($primary_category) > 0){
             foreach ($primary_category as $key => $value) {
                $primary_category_html .= "<option value=".$value->id." selected >".$value->category_name."</option>";
             }  
             $primary_category_id = $primary_category[0]->id;
        }else{
            $primary_category_html .= "<option disabled selected>No primary category found.</option>"; 
            $primary_category_id = 0;
        } 

        if(count($subcategory) > 0){
             foreach ($subcategory as $key => $value) {
                $subcategory_html .= "<option value=".$value->id." selected >".$value->subcategory."</option>";
             }  
             $subcategory_id = $subcategory[0]->id;
        }else{
            $subcategory_html .= "<option disabled selected>No subcategory found.</option>"; 
            $subcategory_id = 0;
        } 

        $response = [
                "primary_category_html"  => $primary_category_html,
                "primary_category_id"    => $primary_category_id,
                'subcategory_html' => $subcategory_html,
                'subcategory_id' => $subcategory_id,
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
                    ExampleProduct::where('id',$value)->update(['status' => 1]);  
                }
                $message = 'Data activated successfully.';
            }elseif($request->action == 'inactive'){
                foreach ($bulk_ids as $key => $value) { 
                    ExampleProduct::where('id',$value)->update(['status' => 0]); 
                }
                $message = 'Data inactivated successfully.';
            }elseif($request->action == 'delete'){
                foreach ($bulk_ids as $key => $value) {   
                        ExampleProduct::where('id',$value)->update(['status' => 2]); 
                        $error = [];   
                }
                if(count($error) > 0){
                    $message = "Some categories are assigned to child categories so you can't delete that.";
                }else{
                    $message = 'Data deleted successfully.';
                }
                
            }else{
                return redirect()->back()->with('error', 'Please select any bulk action.');  
            }
            
            return redirect()->route('example.products')->with('success', $message);
        }else{
            return redirect()->back()->with('error', 'Please select any record.');  
        } 
    } 
 
}
