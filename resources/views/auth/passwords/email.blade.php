@extends('dashboard.layouts.auth')
@section('title', __('backend.forgotPassword'))
@section('content')
    <div class="center-block w-xxl p-t-3">
        <div class="p-a-md box-color r box-shadow-z4 text-color">
            <div class="text-center">
                <img class="logo-img" alt="" src="{{ asset('assets/frontend/logo/logo.png')}}">
            </div>
            <div class="m-y text-muted text-center">
                {{ __('backend.forgotPassword') }}
            </div>
            <div class="text-muted text-left">
                <p class="text-xs m-t">{{ __('backend.enterYourEmail') }}</p>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
             @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form name="reset" method="POST" action="{{ url('/'.env('BACKEND_PATH').'/forgot/user') }}" id="forgotPassword">
                {{ csrf_field() }}
                <div class="md-form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email" value="{{ old('email') }}" class="md-input" required>
                    <label>{{ __('backend.yourEmail') }}</label>
                </div>
                @if ($errors->has('email'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        {{ $errors->first('email') }}
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block validate">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <button type="submit"
                        class="btn primary btn-block p-x-md" id="submitDetail">{{ __('backend.sendPasswordResetLink') }}</button>
            </form>

            <p id="alerts-container"></p>
            <div class="p-v-lg text-center">{{ __('backend.returnTo') }} <a href="{{ url('/'.env('BACKEND_PATH').'/login') }}"
                                                                            class="text-primary _600">{{ __('backend.signIn') }}</a>
        </div>
        </div>
    </div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">   
$(document).ready(function () {
$('#submitDetail').on('click', function () {   
// alert('hello')   
var myForm = $("form#forgotPassword");   
if (myForm) {   
$(this).prop('disabled', true);   
$(myForm).submit();   
}   
});   
});   
</script>
@endsection

