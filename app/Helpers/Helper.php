<?php

namespace App\Helpers;

use App; 
use Illuminate\Support\Facades\DB;
use App\Models\Setting; 
use App\Models\WebmasterSetting;
use App\Models\EmailTemplate;
use App\Models\MainUsers; 
use App\Models\User;
use App\Models\ProductAttributeSubcategory;
use App\Models\ProductAttributeCategory;
use App\Models\PrimaryPackaging;
use App\Models\PrimaryPackagingCategory;
use App\Models\PrimaryPackagingSubcategory;
use App\Models\SkuPackagingCategory;
use App\Models\SkuPackagingSubcategory;
use App\Models\CountriesOfOriginProductCategory;
use App\Models\CountriesOfOriginSubcategory;  
use App\Models\CountriesOfDestinationProductCategory;
use App\Models\CountriesOfDestinationSubcategory;  
use App\Models\CapacitySubcategory;
use App\Models\CapacityProductCategory;   
use App\Models\CertificateSubcategory;
use App\Models\CertificateProductCategory;  
use App\Models\UserRole;  
use App\Models\ProductCategory;
use App\Models\ProductAttribute;
use App\Models\SubCategory;
use App\Models\RoleModulePermission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use GeoIP;
use Config;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Session;
use PhpParser\Builder\Class_;

class Helper
{

    static function createStatus($status = "")
    {
        $flag = 1;
        if (!empty($status)) {
            $flag = 0;
        }
        return $flag;
    }

    static function GeneralWebmasterSettings($var)
    {
        $WebmasterSetting = WebmasterSetting::find(1);
        return @$WebmasterSetting->$var?:20;
    }

    static function GeneralSiteSettings($var)
    {
        $Setting = Setting::find(1);
        return $Setting->$var;
    }

    static function Settings($var)
    {
        $Setting = Setting::find(1);
        return $Setting->$var;
    } 
    static function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    static function get_day_name($timestamp) {

        $date = date('d/m/Y', strtotime($timestamp));
        if($date == date('d/m/Y')) {
          $date = 'Today ' . date('h:i A', strtotime($timestamp));
        } 
        else if($date == date('d/m/Y',(time() - (24 * 60 * 60))) ) {
          $date = 'Yesterday ' . date('h:i A', strtotime($timestamp));
        } 
        else {
            $date = date( env('DATE_FORMAT','Y-m-d') . ' h:i A', strtotime($timestamp));
        }
        return $date;
    } 

    static function changeDateFormate($date, $date_format)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
    }
 

    static function getEmailTemplateData($id){ 
        return $template_data = EmailTemplate::find($id);
    }



    static function getEmailtemplateContentForgotpassword($id, $email = "", $password = "", $name = "", $url = "", $logo = "")
    {  
        if($email != null){
            $user_id = User::where('email',$email)->where('status',1)->pluck('id')->first();
            $verify_email_url = $url.'verify-email/'.base64_encode($user_id); 
        }else{
            $user_id = null;
            $verify_email_url = ''; 
        }  
        $setting = Setting::first(); 
        $facebook = isset($setting->facebook_link) ? $setting->facebook_link : '';
        $twitter = isset($setting->twitter_link) ? $setting->twitter_link : '';
        $instagram =  isset($setting->instagram_link) ? $setting->instagram_link : ''; 
        $emailtemp = Helper::getEmailTemplateData($id);
        // echo "<pre>";print_r($emailtemp);exit();
        if (isset($logo) && !empty($logo)) {
            $logo = $logo;
        } else {
            $logo = asset('assets/frontend/logo/logo.png');
        }
        $login_url = "https://plp.vrinsoft.in/admin/login";
        $copyright = WebmasterSetting::pluck('copyright_en')->first();
        $vars = array(
            '{{$email}}' => $email,
            '{{$name}}' => $name,
            '{{ $url }}' => $url,
            '{{$logo}}' => $logo,
            '{{$password}}' => $password,
            '{{$copyright}}' => $copyright, 
            '{{$login_url}}' => $login_url, 
            '{{$verify_email_url}}' => $verify_email_url,
            '{{$settingfacebook}}' =>  $facebook,
            '{{$settingtwitter}}' => $twitter,
            '{{$settinginstagram}}' => $instagram,
            '{$phoneNumber}' => isset($setting->phone) ? $setting->phone : '',
            '{$supportemail}' => isset($setting->support_email) ? $setting->support_email : '',
        );

        $email = strtr(urldecode($emailtemp['content']), $vars); 
        return $email;
    }

    static function getEmailtemplateContentRegistration($id, $email = "", $name = "", $url = "", $logo = "")
    {
        /*$setting = Setting::first();*/
        $setting = Setting::first();
        // dd($setting);
        $facebook = isset($setting->facebook_link) ? $setting->facebook_link : '';
        $twitter = isset($setting->twitter_link) ? $setting->twitter_link : '';
        $instagram =  isset($setting->instagram_link) ? $setting->instagram_link : '';

        $emailtemp = Helper::getEmailTemplateData(1,1);

        if (isset($logo) && !empty($logo)) {
            $logo = $logo;
        } else {
            $logo = asset('assets/frontend/logo/logo.png');
        }
        $vars = array(
            '{{$email}}' => $email,
            '{{$name}}' => $name,
            '{{ $url }}' => $url,
            '{{$logo}}' => $logo,
            '{{$settingfacebook}}' =>  $facebook,
            '{{$settingtwitter}}' => $twitter,
            '{{$settinginstagram}}' => $instagram,
            '{$phoneNumber}' => isset($setting->phone) ? $setting->phone : '',
            '{$supportemail}' => isset($setting->support_email) ? $setting->support_email : '',
        );

        $email = strtr(urldecode($emailtemp['content']), $vars);
        // echo $email;exit();
        return $email;
    }

    static function getEmailtemplateContentReportAgent($id, $language_id, $email, $user_email, $name, $phone, $country_code, $message, $agent_id, $url = "", $logo = "")
    {

        $setting = Setting::first();
        $agent_phone_number = "";
        $agent_email = "";
        $agent_name = "";

        if($agent_id) {
            $agent_details = MainUsers::find($agent_id);
            $agent_phone_number = urldecode($agent_details->country_code) . ' ' . $agent_details->mobile_number;
            $agent_email = urldecode($agent_details->email);
            $agent_name = urldecode($agent_details->full_name);
        }
        $facebook = isset($setting->facebook_link) ? $setting->facebook_link : '';
        $twitter = isset($setting->twitter_link) ? $setting->twitter_link : '';
        $instagram =  isset($setting->instagram_link) ? $setting->instagram_link : '';


        $phone_number = $country_code . " " . $phone;
        
        $emailtemp = Helper::getEmailTemplateData($language_id,$id);

        if (isset($logo) && !empty($logo)) {
            $logo = $logo;
        } else {
            $logo = asset('assets/frontend/logo/logo.png');
        }
        $vars = array(
            '{{$email}}' => $user_email,
            '{{$full_name}}' => $name,
            '{{$phone}}' => $phone_number,
            '{{$report_message}}' => $message,
            '{{$agent_email}}' => @$agent_email ?: "",
            '{{$agent_name}}' => @$agent_name ?: "",
            '{{$agent_contact}}' => $agent_phone_number,
            '{{ $url }}' => $url,
            '{{$logo}}' => $logo,
            '{{$settingfacebook}}' =>  $facebook,
            '{{$settingtwitter}}' => $twitter,
            '{{$settinginstagram}}' => $instagram,
            '{$phoneNumber}' => @$setting->phone ?: '',
            '{$supportemail}' => @$setting->support_email ?: '',
        );

        $email = strtr(urldecode($emailtemp->content), $vars);
        // echo $email;exit();
        return $email;
    }

    static function getEmailtemplateContentPropertyInquiry($id, $language_id, $email, $user_email, $name, $phone, $country_code, $message, $agent_id, $property_id, $url = "", $logo = "")
    {
        /*$setting = Setting::first();*/
        $setting = WebmasterSetting::first();
        $agent_details = MainUsers::find($agent_id);
        $property_details = Property::find($property_id);
        $facebook = isset($setting->facebook_link) ? $setting->facebook_link : '';
        $twitter = isset($setting->twitter_link) ? $setting->twitter_link : '';
        $instagram =  isset($setting->instagram_link) ? $setting->instagram_link : '';


        $phone_number = $country_code . " " . $phone;
        $agent_phone_number = urldecode($agent_details->country_code) . ' ' . $agent_details->mobile_number;
        $emailtemp = Helper::getEmailTemplateData($language_id,$id);

        if (isset($logo) && !empty($logo)) {
            $logo = $logo;
        } else {
            $logo = asset('assets/frontend/logo/logo.png');
        }
        $vars = array(
            '{{$email}}' => $user_email,
            '{{$full_name}}' => $name,
            '{{$phone}}' => $phone_number,
            '{{$inquiry_message}}' => $message,
            '{{$agent_name}}' => urldecode($agent_details->full_name),
            '{{ $url }}' => $url,
            '{{$logo}}' => $logo,
            '{{$settingfacebook}}' =>  $facebook,
            '{{$settingtwitter}}' => $twitter,
            '{{$settinginstagram}}' => $instagram,
            '{{$property_name}}' => @$property_details->property_name ?: "",
            '{{$property_id}}' => @$property_details->property_id ?: "",
            '{{$property_address}}' => @$property_details->property_address ?: "",
            '{$phoneNumber}' => @$setting->phone ?: '',
            '{$supportemail}' => @$setting->support_email ?: '',
        );

        $email = strtr(urldecode($emailtemp->content), $vars);
        // echo $email;exit();
        return $email;
    }


    static function getEmailtemplateContentAddProperty($id, $language_id, $agent_email, $full_name, $phone, $country_code, $property, $url = "", $logo = "")
    {
        /*$setting = Setting::first();*/
        $setting = WebmasterSetting::first();
        $facebook = isset($setting->facebook_link) ? $setting->facebook_link : '';
        $twitter = isset($setting->twitter_link) ? $setting->twitter_link : '';
        $instagram =  isset($setting->instagram_link) ? $setting->instagram_link : '';


        $phone_number = $country_code . " " . $phone;
        // $agent_phone_number = urldecode($agent_details->country_code) . ' ' . $agent_details->mobile_number;
        $emailtemp = Helper::getEmailTemplateData($language_id,$id);

        if (isset($logo) && !empty($logo)) {
            $logo = $logo;
        } else {
            $logo = asset('assets/frontend/logo/logo.png');
        }
        $vars = array(
            '{{$email}}' => $agent_email,
            '{{$full_name}}' => $full_name,
            '{{$phone}}' => $phone_number,
            '{{ $url }}' => $url,
            '{{$logo}}' => $logo,
            '{{$settingfacebook}}' =>  $facebook,
            '{{$settingtwitter}}' => $twitter,
            '{{$settinginstagram}}' => $instagram,
            '{{$property_name}}' => @$property->property_name ?: "",
            '{{$property_id}}' => @$property->property_id ?: "",
            '{{$property_address}}' => @$property->property_address ?: "",
            '{$phoneNumber}' => @$setting->phone ?: '',
            '{$supportemail}' => @$setting->support_email ?: '',
            '{{$property_title}}' => @$property->property_name ?: "",
            '{{$property_for}}' => @$property->property_for ? Helper::getLabelValueByKey(config('constants.PROPERTY_FOR.'.$property->property_for.'.label_key'), $language_id) : "",
            '{{$property_price}}' => (@$property->base_price ?: 0) . ' ' . Helper::getDefaultCurrency(),
        );

        $email = strtr(urldecode($emailtemp->content), $vars);
        // echo $email;exit();
        return $email;
    }


    static function getEmailtemplateContentApprovedProperty($id, $language_id, $agent_email, $full_name, $phone, $country_code, $property, $url = "", $logo = "")
    {
        /*$setting = Setting::first();*/
        $setting = WebmasterSetting::first();
        $facebook = isset($setting->facebook_link) ? $setting->facebook_link : '';
        $twitter = isset($setting->twitter_link) ? $setting->twitter_link : '';
        $instagram =  isset($setting->instagram_link) ? $setting->instagram_link : '';


        $phone_number = $country_code . " " . $phone;
        // $agent_phone_number = urldecode($agent_details->country_code) . ' ' . $agent_details->mobile_number;
        $emailtemp = Helper::getEmailTemplateData($language_id,$id);

        if (isset($logo) && !empty($logo)) {
            $logo = $logo;
        } else {
            $logo = asset('assets/frontend/logo/logo.png');
        }
        $vars = array(
            '{{$email}}' => $agent_email,
            '{{$full_name}}' => $full_name,
            '{{$username}}' => $full_name,
            '{{$phone}}' => $phone_number,
            '{{ $url }}' => $url,
            '{{$logo}}' => $logo,
            '{{$settingfacebook}}' =>  $facebook,
            '{{$settingtwitter}}' => $twitter,
            '{{$settinginstagram}}' => $instagram,
            '{{$property_name}}' => @$property->property_name ?: "",
            '{{$property_id}}' => @$property->property_id ?: "",
            '{{$property_address}}' => @$property->property_address ?: "",
            '{$phoneNumber}' => @$setting->phone ?: '',
            '{$supportemail}' => @$setting->support_email ?: '',
            '{{$property_title}}' => @$property->property_name ?: "",
            '{{$property_for}}' => @$property->property_for ? Helper::getLabelValueByKey(config('constants.PROPERTY_FOR.'.$property->property_for.'.label_key'), $language_id) : "",
            '{{$property_price}}' => (@$property->base_price ?: 0) . ' ' . Helper::getDefaultCurrency(),
        );

        $email = strtr(urldecode($emailtemp->content), $vars);
        // echo $email;exit();
        return $email;
    }

    static function GetRolePermission($auth_id,$role_module_id)
    {
        $user_role_id = UserRole::where('user_id',$auth_id)->pluck('role_id')->first();
        $allowed_permissions = RoleModulePermission::where('role_id',$user_role_id)->where('role_module_id',$role_module_id)->first(); 
        return $allowed_permissions; 
    } 

    static function GetProductSubcategories($product_attribute_id)
    {
        $subcategory = ProductAttributeSubcategory::with('subcategory')->where('product_attribute_id',$product_attribute_id)->get(); 
        if(count($subcategory) > 0){
            foreach ($subcategory as $key => $value) {
                if(isset($value['subcategory']) && $value['subcategory'] != null){
                    $subcategory_name[$value['subcategory']['id']] = $value['subcategory']['artical_number'].' '.$value['subcategory']['subcategory'];
                }else{
                    $subcategory_name[$value['subcategory']['id']] = '-';
                } 
            }     
        }else{
            $subcategory_name = [];
        }
        
        return $subcategory_name; 
    }

    static function GetProductCategories($product_attribute_id)
    {
        $product_categories = ProductAttributeCategory::with('productcategory')->where('product_attribute_id',$product_attribute_id)->get(); 
        if(count($product_categories) > 0){ 
            foreach ($product_categories as $key => $value) {
                if(isset($value['productcategory']) && $value['productcategory'] != null){
                    $product_category_name[$value['productcategory']['id']] = $value['productcategory']['artical_number'].' '.$value['productcategory']['product_category'];
                }else{
                    $product_category_name[$value['productcategory']['id']] = '-';
                } 
            }     
        }else{
            $product_category_name = [];
        } 
        return $product_category_name; 
    } 


    static function CheckProductCategoryIsExists($trimmed_str)
    { 
        $is_exists_product_category = [];
        foreach ($trimmed_str as $key => $value) {
            $data = explode(" ", $value, 2);  
            if(count($data) > 1){ 
                $is_exists_product_category[$key] = ProductCategory::where('product_category',$data[1])->where('status',1)->count(); 
            } 
        } 

        $result =  [];
        foreach ($is_exists_product_category as $key => $value) {
            if($value == 0){
                $result['not_matched'][] = $trimmed_str[$key];
            }
        }   
        if(isset($result['not_matched']) && count($result['not_matched']) > 0){
            $result['all_matched'] = false; 
        }else{
            $result['all_matched'] = true; 
        }  
        return $result;
    } 

    static function CheckSubategoryIsExists($trimmed_str)
    { 
        $is_exists_subcategory = [];
        foreach ($trimmed_str as $key => $value) {
            $data = explode(" ", $value, 2); 
            if(count($data) > 1){
                $is_exists_subcategory[$key] = SubCategory::where('subcategory',$data[1])->where('status',1)->count();
            } 
        }

        $result =  [];
        foreach ($is_exists_subcategory as $key => $value) {
            if($value == 0){
                $result['not_matched'][] = $trimmed_str[$key];
            }
        }  
        if(isset($result['not_matched']) && count($result['not_matched']) > 0){
            $result['all_matched'] = false; 
        }else{
            $result['all_matched'] = true; 
        } 

        return $result;
    } 

    static function CheckSubategoryId($subcategory_name)
    { 
        $subcategory_id = [];
        foreach ($subcategory_name as $key => $value) {
            $data = explode(" ", $value, 2); 
            if(count($data) > 1){
                $subcategory_id[] = SubCategory::where('subcategory',$data[1])->where('status',1)->pluck('id')->first(); 
            } 
        } 
        return $subcategory_id;
    }

    static function CheckProductCategoryId($product_category_name)
    { 
        $product_category_id = [];
        foreach ($product_category_name as $key => $value) {
            $data = explode(" ", $value, 2); 
            if(count($data) > 1){
                $product_category_id[] = ProductCategory::where('product_category',$data[1])->where('status',1)->pluck('id')->first(); 
            } 
        }     
        return $product_category_id;
    }

    static function CheckUniquePosition($position)
    { 
        $product_category_id = ProductAttribute::where('position',$position)->where('status','!=',2)->count(); 
        if($product_category_id > 0){
            return 1;
        }else{
            return 0;
        }  
    } 
       
    //////////////////////////************** Primary Packaging ********************////////////////////////////////
    


    static function GetPrimaryPackagingSubcategories($primary_packaging_id)
    {
        $subcategory = PrimaryPackagingSubcategory::with('subcategory')->where('primary_packaging_id',$primary_packaging_id)->get(); 
        if(count($subcategory) > 0){
            foreach ($subcategory as $key => $value) {
                if(isset($value['subcategory']) && $value['subcategory'] != null){
                    $subcategory_name[$value['subcategory']['id']] = $value['subcategory']['artical_number'].' '.$value['subcategory']['subcategory'];
                }else{
                    $subcategory_name[$value['subcategory']['id']] = '-';
                } 
            }     
        }else{
            $subcategory_name = [];
        }
        
        return $subcategory_name; 
    }

    static function GetPrimaryPackagingCategories($primary_packaging_id)
    {
        $product_categories = PrimaryPackagingCategory::with('productcategory')->where('primary_packaging_id',$primary_packaging_id)->get(); 
        if(count($product_categories) > 0){ 
            foreach ($product_categories as $key => $value) {
                if(isset($value['productcategory']) && $value['productcategory'] != null){
                    $product_category_name[$value['productcategory']['id']] = $value['productcategory']['artical_number'].' '.$value['productcategory']['product_category'];
                }else{
                    $product_category_name[$value['productcategory']['id']] = '-';
                } 
            }     
        }else{
            $product_category_name = [];
        } 
        return $product_category_name; 
    }


    //////////////////////////************** SKU Packaging ********************////////////////////////////////
    


    static function GetSkuPackagingSubcategories($sku_packaging_id)
    {
        $subcategory = SkuPackagingSubcategory::with('subcategory')->where('sku_packaging_id',$sku_packaging_id)->get(); 
        if(count($subcategory) > 0){
            foreach ($subcategory as $key => $value) {
                if(isset($value['subcategory']) && $value['subcategory'] != null){
                    $subcategory_name[$value['subcategory']['id']] = $value['subcategory']['artical_number'].' '.$value['subcategory']['subcategory'];
                }else{
                    $subcategory_name[$value['subcategory']['id']] = '-';
                } 
            }     
        }else{
            $subcategory_name = [];
        }
        
        return $subcategory_name; 
    }

    static function GetSkuPackagingCategories($sku_packaging_id)
    {
        $product_categories = SkuPackagingCategory::with('productcategory')->where('sku_packaging_id',$sku_packaging_id)->get(); 
        if(count($product_categories) > 0){ 
            foreach ($product_categories as $key => $value) {
                if(isset($value['productcategory']) && $value['productcategory'] != null){
                    $product_category_name[$value['productcategory']['id']] = $value['productcategory']['artical_number'].' '.$value['productcategory']['product_category'];
                }else{
                    $product_category_name[$value['productcategory']['id']] = '-';
                } 
            }     
        }else{
            $product_category_name = [];
        } 
        return $product_category_name; 
    }

     //////////////////////////************** Countries of Origin ********************////////////////////////////////
    


    static function GetCountriesOfOriginSubcategories($countries_of_origin_id)
    {
        $subcategory = CountriesOfOriginSubcategory::with('subcategory')->where('countries_of_origin_id',$countries_of_origin_id)->get();  
        if(count($subcategory) > 0){
            foreach ($subcategory as $key => $value) { 
                if(isset($value['subcategory']) && $value['subcategory'] != null){
                    $subcategory_name[$value['subcategory']['id']] = $value['subcategory']['artical_number'].' '.$value['subcategory']['subcategory'];
                }else{
                    $subcategory_name[$value['subcategory']['id']] = '-';
                } 
            }     
        }else{
            $subcategory_name = [];
        }
        
        return $subcategory_name; 
    }

    static function GetCountriesOfOriginProductCategories($countries_of_origin_id)
    {
        $product_categories = CountriesOfOriginProductCategory::with('productcategory')->where('countries_of_origin_id',$countries_of_origin_id)->get(); 
        if(count($product_categories) > 0){ 
            foreach ($product_categories as $key => $value) {
                if(isset($value['productcategory']) && $value['productcategory'] != null){
                    $product_category_name[$value['productcategory']['id']] = $value['productcategory']['artical_number'].' '.$value['productcategory']['product_category'];
                }else{
                    $product_category_name[$value['productcategory']['id']] = '-';
                } 
            }     
        }else{
            $product_category_name = [];
        } 
        return $product_category_name; 
    } 


     //////////////////////////************** Countries of Destination ********************////////////////////////////////
    


    static function GetCountriesOfDestinationSubcategories($countries_of_destination_id)
    {
        $subcategory = CountriesOfDestinationSubcategory::with('subcategory')->where('countries_of_destination_id',$countries_of_destination_id)->get();  
        if(count($subcategory) > 0){
            foreach ($subcategory as $key => $value) { 
                if(isset($value['subcategory']) && $value['subcategory'] != null){
                    $subcategory_name[$value['subcategory']['id']] = $value['subcategory']['artical_number'].' '.$value['subcategory']['subcategory'];
                }else{
                    $subcategory_name[$value['subcategory']['id']] = '-';
                } 
            }     
        }else{
            $subcategory_name = [];
        }
        
        return $subcategory_name; 
    }

    static function GetCountriesOfDestinationProductCategories($countries_of_destination_id)
    {
        $product_categories = CountriesOfDestinationProductCategory::with('productcategory')->where('countries_of_destination_id',$countries_of_destination_id)->get(); 
        if(count($product_categories) > 0){ 
            foreach ($product_categories as $key => $value) {
                if(isset($value['productcategory']) && $value['productcategory'] != null){
                    $product_category_name[$value['productcategory']['id']] = $value['productcategory']['artical_number'].' '.$value['productcategory']['product_category'];
                }else{
                    $product_category_name[$value['productcategory']['id']] = '-';
                } 
            }     
        }else{
            $product_category_name = [];
        } 
        return $product_category_name; 
    } 



     //////////////////////////************** Capacity ********************////////////////////////////////
    


    static function GetCapacitySubcategories($capacity_id)
    {
        $subcategory = CapacitySubcategory::with('subcategory')->where('capacity_id',$capacity_id)->get();  
        if(count($subcategory) > 0){
            foreach ($subcategory as $key => $value) { 
                if(isset($value['subcategory']) && $value['subcategory'] != null){
                    $subcategory_name[$value['subcategory']['id']] = $value['subcategory']['artical_number'].' '.$value['subcategory']['subcategory'];
                }else{
                    $subcategory_name[$value['subcategory']['id']] = '-';
                } 
            }     
        }else{
            $subcategory_name = [];
        }
        
        return $subcategory_name; 
    }

    static function GetCapacityProductCategories($capacity_id)
    {
        $product_categories = CapacityProductCategory::with('productcategory')->where('capacity_id',$capacity_id)->get(); 
        if(count($product_categories) > 0){ 
            foreach ($product_categories as $key => $value) {
                if(isset($value['productcategory']) && $value['productcategory'] != null){
                    $product_category_name[$value['productcategory']['id']] = $value['productcategory']['artical_number'].' '.$value['productcategory']['product_category'];
                }else{
                    $product_category_name[$value['productcategory']['id']] = '-';
                } 
            }     
        }else{
            $product_category_name = [];
        } 
        return $product_category_name; 
    } 


     //////////////////////////************** Capacity ********************////////////////////////////////
    


    static function GetCertificateSubcategories($certificate_id)
    {
        $subcategory = CertificateSubcategory::with('subcategory')->where('certificate_id',$certificate_id)->get();  
        if(count($subcategory) > 0){
            foreach ($subcategory as $key => $value) { 
                if(isset($value['subcategory']) && $value['subcategory'] != null){
                    $subcategory_name[$value['subcategory']['id']] = $value['subcategory']['artical_number'].' '.$value['subcategory']['subcategory'];
                }else{
                    $subcategory_name[$value['subcategory']['id']] = '-';
                } 
            }     
        }else{
            $subcategory_name = [];
        }
        
        return $subcategory_name; 
    }

    static function GetCertificateProductCategories($certificate_id)
    {
        $product_categories = CertificateProductCategory::with('productcategory')->where('certificate_id',$certificate_id)->get(); 
        if(count($product_categories) > 0){ 
            foreach ($product_categories as $key => $value) {
                if(isset($value['productcategory']) && $value['productcategory'] != null){
                    $product_category_name[$value['productcategory']['id']] = $value['productcategory']['artical_number'].' '.$value['productcategory']['product_category'];
                }else{
                    $product_category_name[$value['productcategory']['id']] = '-';
                } 
            }     
        }else{
            $product_category_name = [];
        } 
        return $product_category_name; 
    }        
}
