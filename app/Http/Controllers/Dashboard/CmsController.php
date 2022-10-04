<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\WebmasterSection;
use App\Models\Cms; 
use Auth;
use File;
use Illuminate\Config;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use DB;
use Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToArray;
use Yajra\Datatables\Datatables;

class CmsController extends Controller
{
    private $title = "Cms"; 

    public function __construct()
    { 
        $this->middleware('auth');
        if (Auth::user() != null &&  Auth::user()->status != 1 || Auth::user()->email_verified != 1) {  
            Auth::logout(); 
            Session::flush();
            return redirect()->route('admin.login');
        }
    }

    /**
     * Display a listing of the resource.
     * string $stat
     * @return \Illuminate\Http\Response
     */

    public function index()
    { 
        $cms = Cms::get();  
        $cmsData = count($cms);  
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),5); 
        return view("dashboard.cms.list",compact("cms","cmsData","allowed_permissions"));

    }




    public function anyData(Request $request) 
    {   


        $sort='cms.created_at';  
        $sortBy='DESC';  
        $totalAr = Cms::where('status','!=',2)->orderBy($sort,$sortBy);   
        $totalRecords = $totalAr->get()->count();

        $totalAr = $totalAr->get();  
        $data_arr=[];
        $data_arr['data'] = [];
        foreach ($totalAr as $key => $data) 
        {   
            $RoleEdit =  route('cms.edit',['id'=>base64_encode($data->id)]);
            $RoleShow =  route('cms.show',['id'=>base64_encode($data->id)]);
            $RoleDelete =  route('cms.delete',['id'=>base64_encode($data->id)]);
            $options = "";   

            $allowed_permissions = Helper::GetRolePermission(Auth::id(),5); 

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

            $data_arr['data'][] =array(     
                 isset($data->page_title) ? $data->page_title: '', 
                // $status, 
                 $options, 
            );   
        }  

        return response()->json($data_arr);  
    }

    public function create()
    { 
        return view("dashboard.cms.create");
    }


    public function store(Request $request)
    {     
        $this->validateRequest();
        $cms = new Cms();

        $cms->page_title = $request->page_name; 
        $cms->page_content = $request->page_content;
        $cms->status = 1;  
        $cms->save(); 
        
        return redirect()->route('cms')->with('success', $this->title.' '.__('backend.addDone'));
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($encode_id)
    {   
        $id = base64_decode($encode_id); 
        $cms = Cms::find($id); 
        return view('dashboard.cms.edit', compact('cms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {  
        $this->validateRequest();

        $cms = Cms::find($id);  
        $cms->page_title = $request->page_name; 
        $cms->page_content = $request->page_content;
        $cms->status = 1; 
        $cms->save(); 

        return redirect()->route('cms')->with('success', $this->title.' '.__('backend.saveDone'));
    }


    public function destroy(Request $request)
    {  
        Cms::where('id',$request->id)->update(['status' => 2]); 
        return 1;     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function show($encode_id)
    { 
        $id = base64_decode($encode_id); 
        $cms = Cms::find($id); 
        return view('dashboard.cms.show',compact('cms'));
    } 

    public function validateRequest($id="")
    {

        if($id !="")
        {
            $validateData =request()->validate([
                'page_name' => 'required|max:50',
                'page_content' => 'required',
            ]); 

        }else{

            $validateData =request()->validate([
                'page_name' => 'required|max:50',
                'page_content' => 'required',
            ]);
            
        }

        return $validateData;
    }
}
