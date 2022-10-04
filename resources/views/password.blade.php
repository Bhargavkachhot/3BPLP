<?php
// echo "string1";exit();
use App\Helpers;

 $content = Helper::getEmailtemplateContentForgotpassword($id,$email,$password,$name,$url,$logo);
?>
<div>{!! $content !!}</div>

