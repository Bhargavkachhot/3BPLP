<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\WebmasterSection;
use App\Models\EmailTemplate;
use App\Models\Language;
use Auth;
use File;
use Illuminate\Config;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use DB;
use Session;
use Yajra\Datatables\Datatables;

class EmailTemplateController extends Controller
{

    // Define Default Variables

    public function __construct()
    {
        // if( !(\Helper::check_permission(7,1))) {
        //     return redirect()->route('NoPermission');
        // }
        $this->middleware('auth');
        if (Auth::user() != null) {
            if(Auth::user()->status != 1 || Auth::user()->email_verified != 1){
                Auth::logout(); 
                Session::flush();
                return redirect()->route('admin.login');
            }   
        }

    }

    /**
     * Display a listing of the resource.
     * string $stat
     * @return \Illuminate\Http\Response
     */

    public function index()
    { 
        $emails = EmailTemplate::get();

        $emailtemplate = count($emails); 
        $allowed_permissions = Helper::GetRolePermission(Auth::id(),3);     
        return view("dashboard.emailtemplate.list",
            compact("emails","emailtemplate","allowed_permissions"));

    }

    public function create()
    {
        // if( !(\Helper::check_permission(7,2))) {
        //     return redirect()->route('NoPermission');
        // }

        // General for all pages
        // $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();

        return view("dashboard.emailtemplate.create");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // if( !(\Helper::check_permission(7,2))) {
        //     return redirect()->route('NoPermission');
        // }
        //dd($request);
        $this->validateRequest();
        $emailtemplate = new EmailTemplate();
        $emailtemplate->title = $request->title;
        $emailtemplate->subject = $request->subject;
        $emailtemplate->content = $request->content;
        $emailtemplate->status = 1;
        $emailtemplate->created_at = date('Y-m-d H:i:s');
        $emailtemplate->save(); 

        return redirect()->route('emailtemplate')
            ->with('success', 'Email Template created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if( !(\Helper::check_permission(7,3))) {
        //     return redirect()->route('NoPermission');
        // }

        $emailtemplate = EmailTemplate::find($id);
          // General for all pages
        // $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
         return view('dashboard.emailtemplate.edit', compact('emailtemplate'));
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
        // if( !(\Helper::check_permission(7,3))) {
        //     return redirect()->route('NoPermission');
        // }

        $this->validateRequest();
        $emailtemplate = EmailTemplate::find($id);
        $emailtemplate->title = $request->title;
        $emailtemplate->subject = $request->subject;
        $emailtemplate->content = $request->content;
        $emailtemplate->status = 1;
        $emailtemplate->updated_at = date('Y-m-d H:i:s');
        $emailtemplate->save(); 

        return redirect()->route('emailtemplate')->with('success', 'Email Template updated successfully.');
    }
    public function validateRequest($id="")
    {

        if($id !="")
        {
            $validateData =request()->validate([
                'title' => 'required',
                'subject' => 'required',
                'content' => 'required',
            ]);

        }else{

            $validateData =request()->validate([
                'title' => 'required',
                'subject' => 'required',
                'content' => 'required',
            ]);
            
        }

        return $validateData;
    }

     public function show($id)
    {
        // if( !(\Helper::check_permission(7,1))) {
        //     return redirect()->route('NoPermission');
        // }

        $emailtemplate = EmailTemplate::find($id);
        // dd($emailtemplate->toArray());
        // General for all pages
        // $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        return view('dashboard.emailtemplate.show', compact('emailtemplate'));
    }
    
    // multi language START

    public function multiLang($parentId,$lang_id){
        // if( !(\Helper::check_permission(7,2)) || !(\Helper::check_permission(7,3)) ) {
        //     return redirect()->route('NoPermission');
        // }
        // dd($parentId,$lang_id);
        $emailTampltequery = EmailTemplate::where('parent_id','=',$parentId)->where('language_id','=',$lang_id);
        $istamplteExists = $emailTampltequery->count();
        $parentData = EmailTemplate::find($parentId);
        $tamplate = [];
        $EmailTemplate =  '';
        if($istamplteExists>0){
            $EmailTemplate = $emailTampltequery->first();
            // dd($EmailTemplate->toArray());
        }
        $languageData = Language::find($lang_id);
        return view('dashboard.emailtemplate.multiLang',compact('EmailTemplate','parentData','languageData'));
    }


    public function storeLang(Request $request){
        // if ( !(\Helper::check_permission(7,2)) || !(\Helper::check_permission(7,3)) ) {
        //     return Redirect::to(route('NoPermission'))->send();
        // }
        $this->validateRequest();
        
        $parentData = EmailTemplate::find($request->template_parent_id);
        $lang_id = (int) $request->template_language_id;
        $parent_id = (int) $parentData->id;
        $template_id = $request->template_id;
        $title = $request->title;
        $subject = $request->title;
        $content = $request->content;

        // echo $lang_id . ' : ' . $parent_id;exit();
        // $language = Language::find($lang_id);
        // dd($lang_id,$parent_id);
        // dd(EmailTemplate::where('language_id', $lang_id)->where( 'parent_id',$parent_id )->toSql());

        $Template = EmailTemplate::updateOrCreate([
            'language_id' => $lang_id,
            'parent_id' => $parent_id,
        ],[
            'title' => $title,
            'subject' => $subject,
            'content' => $content,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // dd($newUser->toSql());
        return redirect()->route('emailtemplate')->with('success', 'Language added in template.');
    }

    // multi language END

    // ----------------------------------------------------------------------

    public function anyData(Request $request) 
    {
        // echo "<pre>";print_r($request->toArray());exit();
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder='';
        if (isset($order_arr[0]['dir']) && $order_arr[0]['dir']!="") {
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        }
        $searchValue = $search_arr['value']; // Search value
        if ($columnIndex==0) {
            $sort='email_template.id';
        }elseif ($columnIndex==1) {
            $sort='email_template.title';
        }elseif ($columnIndex==2) {
            $sort='email_template.subject';
        }else{
            $sort='email_template.id';
        }

        $sortBy='DESC';
        if ($columnSortOrder!="") {
            $sortBy=$columnSortOrder;
        }

        $totalAr = EmailTemplate::where('status','!=',2);
        // echo "<pre>";print_r($totalAr->toArray());exit();
        // dd($totalAr->toSql());
          if ($searchValue!="") {
            $totalAr = $totalAr->where(function ($query) use ($searchValue) {
                $query->orWhere('title', 'like', '%' . $searchValue . '%')
                      ->orWhere('subject', 'like', '%' . $searchValue . '%');
            });
        }


        $totalRecords = $totalAr->get()->count();

        $totalAr = $totalAr->orderBy($sort,$sortBy)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr=[];

        foreach ($totalAr as $key => $data) 
        {   
            $emailtemplateEdit =  route('emailtemplate.edit',['id'=>$data->id]);
            $emailtemplateShow =  route('emailtemplate.show',['id'=>$data->id]);
            $options = "";  

            $allowed_permissions = Helper::GetRolePermission(Auth::id(),3); 

            if(isset($allowed_permissions) && $allowed_permissions->read == 1){
                $options .= '<a class="btn btn-sm show-eyes list" href="'.$emailtemplateShow.'" title="Show Email template"> </a> '; 
            }

            if(isset($allowed_permissions) && $allowed_permissions->update == 1){
                $options .= '<a class="btn btn-sm success paddingset" href="'.$emailtemplateEdit.'" title="Edit"> <small><i class="material-icons">&#xe3c9;</i> </small> </a>'; 
            } 
             
            if($options == ''){
                $options = "-"; 
            }  
            
             

            $data_arr[] =array(
              "id" =>   isset($data->id) ? $data->id: '',
              "title" =>   isset($data->title) ? $data->title: '',
              "subject" =>  isset($data->subject) ? $data->subject : '',
              "options" => $options,
            );
          
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data_arr
         );
        echo json_encode($response);
    }

}
