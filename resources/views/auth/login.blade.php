@extends('dashboard.layouts.auth')
@section('title', __('Admin Login'))
@section('content')
<style type="text/css">
    .pass_eye_box {
    position: absolute;
    right: 0;
    top: 25px;
    cursor: pointer;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <div class="center-block w-xxl p-t-3">
        <div class="p-a-md box-color r box-shadow-z4 text-color m-b-0">
            <div class="text-center">
                <img class="logo-img" alt="" src="{{ asset('assets/frontend/logo/logo.svg') }}">
            </div>
            <div class="m-y text-muted text-center">
                Admin Login
            </div>
            <form name="form" method="POST" action="{{ route('adminLogin') }}">
                {{ csrf_field() }}
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block validate">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                @if (@$errors->any())
                    <div class="alert alert-danger m-b-0">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <?php
                if (Cookie::get('admin_email') !== null) {
                    $email = Cookie::get('admin_email');
                }
                if (Cookie::get('admin_password') !== null) {
                    $password = Cookie::get('admin_password');
                }

                // echo "<pre>";print_r($email);
                // print_r($password);exit();
                ?>
                <div class="md-form-group float-label {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email" value="{{ isset($email) ? $email : '' }}" class="md-input"
                        required>
                    <label>{{ __('backend.connectEmail') }} <span class="valid_field">*</span></label>
                </div>
               <!--  @if ($errors->has('email'))
                    <span class="help-block">
                        <span style="color: red;" class='validate'>{{ $errors->first('email') }}</span>
                    </span>
                @endif -->
                <div class="md-form-group float-label {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" id="password" value="{{ isset($password) ? $password : '' }}"
                        class="md-input" required>
                        <div class="pass_eye_box">
                                                <span toggle="#password-field" class="fa fa-fw fa-eye-slash field_icon toggle-password"></span>

                                            </div>
                    <label>{{ __('backend.connectPassword') }} <span class="valid_field">*</span></label>
                </div>
                <!-- @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif -->
                <div class="m-b-md text-left">
                    <label class="md-check">
                        <input type="checkbox" name="remember_me" {{ isset($password) ? 'checked' : '' }} value="true"><i
                            class="primary"></i> {{ __('backend.keepMeSignedIn') }}
                    </label>
                </div>
                <button type="submit" class="btn primary btn-block p-x-md m-b">{{ __('backend.signIn') }}</button>
            </form>

            <div class="p-v-lg text-center">
                <div class="m-t"><a href="{{ url('/' . env('BACKEND_PATH') . '/forgot-password') }}"
                        class="text-primary _600">{{ __('backend.forgotPassword') }}</a></div>
            </div>

        </div>


    </div>
@endsection
@push('after-scripts')
    <script>
        $(document).ready(function(){


            $("input").addClass("has-value");

$("body").on('click', '.toggle-password', function() {
    // alert('Hey');
  $(this).toggleClass("fa-eye-slash fa-eye");
  var input = $("#password");
  // alert(input)
  if (input.attr("type") === "password") {
    // alert('hey1')
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }

});
});

        $(document).ready(function (){
            $('.close').on('click', function (e) {
                $(this).parents('.alert').hide();
            });
        });
    </script>
@endpush