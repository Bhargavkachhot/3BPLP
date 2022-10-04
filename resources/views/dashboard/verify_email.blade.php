<!DOCTYPE html>
<html lang=" ">
  <head>
    <meta charset="utf-8"/>
<title>{{ __('3B PLP') }}</title>
<meta name="description" content=""/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-barstyle" content="black-translucent"> 
<link rel="icon"  href="{{ asset('assets/frontend/logo/3b_favicon_icon.png')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<meta name="apple-mobile-web-app-title" content="Smartend">
{{-- <base href="{{ route('adminHome') }}"> --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="mobile-web-app-capable" content="yes">
<link rel="shortcut icon" sizes="196x196" href="{{ asset('assets/frontend/logo/logo.png')}}">
@stack('before-styles')
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/animate.css/animate.min.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/animate.css/animate.min.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('assets/dashboard/fonts/glyphicons/glyphicons.css') }}" type="text/css"/>
{{-- <link rel="stylesheet" href="{{ asset('assets/dashboard/fonts/font-awesome/css/font-awesome.min.css') }}" type="text/css"/> --}}
<link rel="stylesheet" href="{{ asset('assets/fa/css/all.min.css') }}" type="text/css"/>
{{-- <link rel="stylesheet" href="{{ asset('assets/fa/css/fontawesome.min.css') }}" type="text/css"/> --}}


<link rel="stylesheet" href="{{ asset('assets/dashboard/fonts/material-design-icons/material-design-icons.css') }}"
      type="text/css"/>
{{-- <link rel="stylesheet" href="{{ asset('assets/dashboard/fonts/materialize-v1.0.0/materialize/css/materialize.min.css') }}" type="text/css"/> --}}

<link rel="stylesheet" href="{{ asset('assets/dashboard/css/bootstrap/dist/css/bootstrap.min.css') }}"
      type="text/css"/>
       
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<link rel="stylesheet" href="{{ asset('assets/dashboard/css/app.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/font.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/topic.css') }}" type="text/css"/>
<link rel="stylesheet" href="{{ asset('assets/dashboard/css/custom.css') }}" type="text/css"/>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('ckeditor/styles.js') }}"></script>

{{-- @if( @Helper::currentLanguage()->direction=="rtl")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/bootstrap-rtl/dist/bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/app.rtl.css') }}">
@endif --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script> 
  </head>
  <body>
    <div class="app" id="app">
      <div id="content" class="app-content box-shadow-z0" role="main">
        <div class="app-header white box-shadow navbar-md">
          <div class="navbar">
            <a class="navbar-brand text-center logo_css" href="{{ route('adminHome') }}">
              <img src="{{ asset('assets/frontend/logo/logo.png')}}" alt="Control" style="width: 130px;    margin-top: 10px;">
              <!-- <span class="hidden-folded inline">USSIE-TEXI</span> -->
            </a>
            <!-- Page title - Bind to $state title -->
            <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>
            <!-- navbar right -->
            <ul class="nav navbar-nav pull-right">
              <li class="nav-item dropdown">
                <a class="nav-link clear" href data-toggle="dropdown">
                  <span class="avatar"> @if (isset(Auth::user()->image) && Auth::user()->image != '') <img src="{{ asset('uploads/users/' . Auth::user()->image) }}" style="vertical-align: middle;
    width: 45px;
    height: 38px;
    border-radius: 50%;" id="img_responsive_profile"> @else <img src="{{ asset('assets/dashboard/images/avatar.png') }}" style="vertical-align: middle;
    width: 45px;
    height: 38px;
    border-radius: 50%;"> @endif <i class="on b-white bottom"></i>
                  </span>
                </a> @if (isset(Auth::user()->id)) <div class="dropdown-menu pull-right dropdown-menu-scale"> @if (@Helper::GeneralWebmasterSettings('inbox_status')) @if (@Auth::user()->permissionsGroup->inbox_status) {{-- <a class="dropdown-item"
                               href="{{ route('webmails') }}"> <span>{{ __('backend.siteInbox') }}</span> @if (Helper::webmailsNewCount() > 0) <span class="label warn m-l-xs">{{ Helper::webmailsNewCount() }}</span> @endif </a> --}} @endif @endif <a class="dropdown-item" href="{{ route('admin.profile', Auth::user()->id) }}">
                    <span>{{ __('backend.profile') }}</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('admin-change-password') }}">
                    <span>Change Password</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a id="logout" class="dropdown-item" href="{{ url('/admin/logout') }}">Logout</a>
                  <form id="logout-form" action="{{ route('main-user-logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                </div> @else <div class="dropdown-menu pull-right dropdown-menu-scale">
                  <a class="dropdown-item" href="{{ route('admin.login') }}">
                    <span>Login</span>
                  </a>
                </div> @endif
              </li>
            </ul>
            <!-- / navbar collapse -->
          </div>
        </div>
        <div class="padding edit-package edit-user">
          <div class="box">
            <div class="box-body">
              <br>
              <br>
              <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4" style="text-align: center;">
                  <img src="{{ asset('assets/dashboard/images/email-verified.png') }}" width="100%" style="text-align: center;">
                  <br>
                  <br>
                  <h6>Email verified successfully.</h6>
                  <br>
                  <a href="{{ route('admin.login') }}">
                    <button type="button" class="btn btn-success">Login to continue </button>
                  </a>
                </div>
                <div class="col-sm-4"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="app-footer">
          <div class="p-a text-xs">
            <div class="pull-right text-muted"> &copy;2022 Copyright <strong>3B PLP</strong>
            </div>
            <div class="nav"> &nbsp; </div>
          </div>
        </div>
      </div>
      <script src="{{ asset('assets/dashboard/js/jquery/dist/jquery.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/dashboard/js/tether/dist/js/tether.min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/bootstrap/dist/js/bootstrap.js') }}" defer></script>
{{-- <script src="{{ asset('assets/dashboard/js/moment/moment.js') }}" defer></script> --}}
<script src="{{ asset('assets/dashboard/js/moment/moment.js') }}" defer></script> 
<!-- core -->
<script src="{{ asset('assets/dashboard/js/underscore/underscore-min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/jQuery-Storage-API/jquery.storageapi.min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/pace/pace.min.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/config.lazyload.js') }}" defer></script>

<script src="{{ asset('assets/dashboard/js/scripts/palette.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-load.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-jp.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-include.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-device.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-form.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-nav.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-screenfull.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-scroll-to.js') }}" defer></script>
<script src="{{ asset('assets/dashboard/js/scripts/ui-toggle-class.js') }}" defer></script>
{{-- <script src="{{ asset('assets/fa/js/all.min.js') }}" defer></script> --}}
{{-- <script src="{{ asset('assets/dashboard/fonts/materialize-v1.0.0/materialize/js/materialize.min.js') }}" defer></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.11/dist/js/bootstrap-select.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.11/dist/js/bootstrap-select.min.js"></script>  -->

<script src="{{ asset('assets/dashboard/js/scripts/app.js') }}" defer></script>
<script type="text/javascript">
    // Restrict user input in a text field
    // create as many regular expressions here as you need:

    function restrictInput(myfield, e, restriction, checkdot){
        var digitsOnly = /[1234567890]/g;
        var integerOnly = /^[0-9\.]$/g;
        // var integerOnly = /^\d{0,15}(\.\d{1,4})?$/g;
        var alphaOnly = /[A-Za-z]/g;
        var usernameOnly = /[0-9A-Za-z\._-]/g;
        var latLong = /^[0-9\.]+$/g;

        if(restriction == 'digits'){
            restrictionType = digitsOnly;
        }

        if(restriction == 'latLong'){
            restrictionType = latLong;
        }

        if(restriction == 'integer'){
            restrictionType = integerOnly;
        }

        if(restriction == 'alpha'){
            restrictionType = alphaOnly;
        }

        if(restriction == 'username'){
            restrictionType = usernameOnly;
        }

        if (!e) var e = window.event
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);

        // if user pressed esc... remove focus from field...
        if (code==27) { this.blur(); return false; }
        // ignore if the user presses other keys
        // strange because code: 39 is the down key AND ' key...
        // and DEL also equals .
        if (!e.ctrlKey && code!=9 && code!=8 && code!=36 && code!=37 && code!=38 && (code!=39 || (code==39 && character=="'")) && code!=40) {
            if (character.match(restrictionType)) {
                if(checkdot == "checkdot"){
                    return !isNaN(myfield.value.toString() + character);
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    function isNumberKey(txt, evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 46) {
          //Check if the text already contains the . character
          if (txt.value.indexOf('.') === -1) {
            return true;
          } else {
            return false;
          }
        } else {
          if (charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
        }
        return true;
    }

    $(".decimal").on("input", function(evt) {
        var self = $(this);
        self.val(self.val().replace(/[^0-9\.]/g, ''));

        if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
        {
          evt.preventDefault();
        }
    });
    

$("input[name='meta_title']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
    return false;
})

$("input[name='artical_number']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
    return false;
})

$("input[name='subcategory']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
    return false;
})

$("input[name='name']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
    return false;
})

$("input[name='role_name']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
    return false;
})

$("input[name='role_slug']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM".indexOf(chr) < 0)
    return false;
})

$("input[name='mobile_number']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("+9876543210".indexOf(chr) < 0)
    return false;
})

$("input[name='password']").keypress(function( e ) {
    if(e.which === 32) 
        return false;
});



$("input[name='position']").keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("1234567890".indexOf(chr) < 0)
    return false;
})

$("#create_form").submit(function (e) {  
     $(this).find("button[type='submit']").prop('disabled',true);
});

$("#create_seo_form").submit(function (e) {  
     $(this).find("button[type='submit']").prop('disabled',true);
});


var max_length = 2;  
    $(document).on("keyup", ".limited_character", function(event) {  
    var len = max_length - $(this).val().length;  
    if(len <= 0){
        event.preventDefault();
       return false;
    }
});


$('#primary_category_id').on('change', function(e) {

});

</script>
      <script type="text/javascript">
        $(document).on("click", "#logout", function(e) {
          // alert('hello')
          e.preventDefault();
          var link = $(this).attr("href");
          // alert(link)
          // return false;
          Swal.fire({
            title: 'Are you sure ?',
            text: "You won't be able to logout!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.isConfirmed) {
              $('#logout-form').submit();
            }
          })
        });
      </script>
      <script type="text/javascript">
        $(document).on("click", "#logout", function(e) {
          // alert('hello')
          e.preventDefault();
          var link = $(this).attr("href");
          // alert(link)
          // return false;
          Swal.fire({
            title: 'Are you sure ?',
            text: "You won't be able to logout!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.isConfirmed) {
              $('#logout-form').submit();
            }
          })
        });
      </script>
  </body>
</html>