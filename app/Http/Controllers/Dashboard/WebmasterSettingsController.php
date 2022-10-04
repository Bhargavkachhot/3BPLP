<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller; 
use App\Http\Requests;
use App\Models\Setting;
use App\Models\Language;
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
use Session;

class WebmasterSettingsController extends Controller
{
    //

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

        // Check Permissions
        if (@Auth::user()->permissions != 0) {
            return Redirect::to(route('NoPermission'))->send();
        }
    }

    public function edit()
    {  
        $setting = Setting::find(1);
        $WebmasterSetting = WebmasterSetting::find(1); 
        if (!empty($WebmasterSetting)) {
            return view("dashboard.webmaster.settings.home", compact("WebmasterSetting", "setting"));
        } else {
            return redirect()->route('adminHome');
        }
    }

    public function update(Request $request)
    {
        //
        // dd($request->all());
        $WebmasterSetting = WebmasterSetting::find(1);
        if (!empty($WebmasterSetting)) { 

            $WebmasterSetting->mail_driver = $request->mail_driver;
            $WebmasterSetting->mail_host = $request->mail_host;
            $WebmasterSetting->mail_port = $request->mail_port;
            $WebmasterSetting->mail_username = $request->mail_username;
            $WebmasterSetting->mail_password = $request->mail_password;
            $WebmasterSetting->mail_encryption = $request->mail_encryption;
            $WebmasterSetting->mail_no_replay = $request->mail_no_replay;
            $WebmasterSetting->mail_title = $request->mail_title;  
            $WebmasterSetting->mail_template = $request->mail_template; 
            $WebmasterSetting->copyright_en = $request->copyright_en;
            $WebmasterSetting->site_title_en = $request->site_title_en;  
            $WebmasterSetting->updated_by = Auth::user()->id;
            $WebmasterSetting->save();

            $settings = Setting::find(1);

            // $WebmasterSetting->support_name =  isset($request->support_name) ? $request->support_name : '';
            $settings->email =  isset($request->email) ? $request->email : '';
            $settings->from_name =  isset($request->from_name) ? $request->from_name : '';
            $settings->support_email =  isset($request->support_email) ? $request->support_email : '';  
            $settings->phone = isset($request->phone) ? $request->phone :"";
            $settings->address = isset($request->address) ? $request->address :"";    
            $settings->save();
 
            // Update .env file
            $env_update = $this->changeEnv([
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_ADDRESS' => $request->mail_no_replay,  
                'DEFAULT_LANGUAGE' => $request->languages_by_default,
                'FRONTEND_PAGINATION' => $request->home_contents_per_page,  
                'GITHUB_STATUS' => $request->login_github_status,
                'GITHUB_ID' => $request->login_github_client_id,
                'GITHUB_SECRET' => $request->login_github_client_secret,
                'BITBUCKET_STATUS' => $request->login_bitbucket_status,
                'BITBUCKET_ID' => $request->login_bitbucket_client_id,
                'BITBUCKET_SECRET' => $request->login_bitbucket_client_secret,
                'GOOGLE_MAPS_KEY' => $request->google_maps_key,
                'FRONTEND_TOPICS_ORDER' => $request->front_topics_order, 
                'GEOIP_STATUS' => $request->geoip_status,
                'GEOIP_SERVICE' => $request->geoip_service,
                'GEOIP_SERVICE_KEY' => $request->geoip_service_key,
                'FIRST_DAY_OF_WEEK' => $request->first_day_of_week,
                'DATE_FORMAT' => $request->date_format,
                'SUPPORT_NAME' => isset($request->support_name) ? $request->support_name : '',
                'SUPPORT_EMAIL' => isset($request->support_email) ? $request->support_email : '', 
            ]); 

            return redirect()->action('Dashboard\WebmasterSettingsController@edit')
                ->with('doneMessage', __('backend.saveDone'))
                ->with('active_tab', $request->active_tab);
        } else {
            return redirect()->route('adminHome');
        }
    }
 

    public function changeEnv($data = array())
    {
        if (count($data) > 0) {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;


            // Loop through given data
            foreach ((array)$data as $key => $value) {

                // add KEY if not exist
                $KEY_EXIST = 0;
                foreach ($env as $env_key => $env_value) {
                    $entry = explode("=", $env_value, 2);
                    if ($entry[0] == $key) {
                        $KEY_EXIST = 1;
                    }
                }
                if (!$KEY_EXIST) {
                    $env[$key] = $key . "=";
                }

                // Loop through .env-data
                foreach ($env as $env_key => $env_value) {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key) {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        } else {
            return false;
        }
    } 

    public function mail_smtp_check(Request $request)
    {
        if ($request->mail_driver == "smtp" && $request->mail_host != "" && $request->mail_port != "") {
            try {
                function server_parse($socket, $expected_response)
                {
                    $server_response = '';
                    while (substr($server_response, 3, 1) != ' ') {
                        if (!($server_response = fgets($socket, 256))) {
                            return 'Error while fetching server response codes';
                        }
                    }

                    if (!(substr($server_response, 0, 3) == $expected_response)) {
                        return $server_response;
                    }
                }

                //Connect to the host on the specified port
                $smtpServer = $request->mail_host;
                $username = $request->mail_username;
                $password = $request->mail_password;
                $port = $request->mail_port;
                $timeout = 20;
                $output = "";

                $socket = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
                if (!$socket) {
                    return json_encode(array("stat" => "error", "error" => "$errstr ($errno)"));
                } else {

                    server_parse($socket, '220');

                    fwrite($socket, 'EHLO ' . $smtpServer . "\r\n");
                    $output .= server_parse($socket, '250');
                    if ($output != "") {
                        $output .= "<br>";
                    }
                    fwrite($socket, 'AUTH LOGIN' . "\r\n");
                    $output .= server_parse($socket, '334');
                    if ($output != "") {
                        $output .= "<br>";
                    }
                    fwrite($socket, base64_encode($username) . "\r\n");
                    $output .= server_parse($socket, '334');
                    if ($output != "") {
                        $output .= "<br>";
                    }
                    fwrite($socket, base64_encode($password) . "\r\n");
                    $output .= server_parse($socket, '235');

                    if ($output == "") {
                        return json_encode(array("stat" => "success"));
                    } else {
                        return json_encode(array("stat" => "error", "error" => $output));
                    }
                }
            } catch (\Exception $e) {
                return json_encode(array("stat" => "error", "error" => "$errstr ($errno)"));
            }
        }
        return json_encode(array("stat" => "error", "error" => "Failed .. no data to connect"));
    }

    public function mail_test(Request $request)
    {
        $WebmasterSetting = WebmasterSetting::find(1);
        if (!empty($WebmasterSetting)) {

            $WebmasterSetting->mail_driver = $request->mail_driver;
            $WebmasterSetting->mail_host = $request->mail_host;
            $WebmasterSetting->mail_port = $request->mail_port;
            $WebmasterSetting->mail_username = $request->mail_username;
            $WebmasterSetting->mail_password = $request->mail_password;
            $WebmasterSetting->mail_encryption = $request->mail_encryption;
            $WebmasterSetting->mail_no_replay = $request->mail_no_replay;
            $WebmasterSetting->save();


            $env_update = $this->changeEnv([
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_ADDRESS' => $request->mail_no_replay,
            ]);

            if ($request->mail_driver == "smtp" && $request->mail_host != "" && $request->mail_port != "") {
                try {
                    $email_subject = "Test Mail From " . env("APP_NAME");
                    $email_body = "This is a Test Mail \r\n
                                    Mail Driver: " . $request->mail_driver . "
                                    Mail Host: " . $request->mail_host . "
                                    Mail Port: " . $request->mail_port . "
                                    Mail Username: " . $request->mail_username . "
                                    Email from: " . $request->mail_no_replay . "
                                    Email to: " . $request->mail_test . "
                                    ";
                    $to_email = $request->mail_test;
                    $to_name = "";
                    $from_email = $request->mail_no_replay;
                    $from_name = env("APP_NAME");
                    Mail::send([], [], function ($message) use ($email_subject, $email_body, $to_email, $to_name, $from_email, $from_name) {
                        $message->from($from_email, $from_name)
                            ->to($to_email, $to_name)
                            ->subject($email_subject)
                            ->setBody($email_body);
                    });
                    return json_encode(array("stat" => "success"));
                } catch (\Exception $e) {
                    return json_encode(array("stat" => "error"));
                }
            }
        }
        return json_encode(array("stat" => "error"));
    }
}
