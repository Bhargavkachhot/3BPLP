@extends('dashboard.layouts.master')
@section('title', 'Change Password')
@section('content')
    <div class="padding edit-package">
        <div class="box">
            <!-- <div id="errorMessage"></div> -->

            <div class="box-header dker">
                <h3><i class="fa fa-cog"></i> Change Password</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <span>Change Password</span>
                    <!-- <a href="javascript:void(0)">Change Password</a> -->
                </small>
            </div>
            {{-- <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("users")}}">
                            <i class="material-icons md-18">×</i>
                        </a>
                    </li>
                </ul>
            </div> --}}
           <!--  @if ($message = Session::get('errorMessage'))
                    <div class="alert alert-danger alert-block validate">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif -->
            <div class="box-body">
                {!! Form::open(['route' => ['admin-update-password'], 'method' => 'POST','name' => 'edit_password', 'id' => 'edit_password', 'autocomplete' => 'off','class'=>'form-horizontal']) !!}
                <div class="form-group row">
                    <label for="name"
                           class="col-sm-4 col-lg-3 form-control-label">Current Password <span class="valid_field">*</span>
                    </label>
                    <div class="col-sm-8 col-lg-9 form-control-label">
                      <input type="password" name="current_password_1" class="form-control" value="{{ Request::old('current_password_1') }}" />
                        <span class="error text-danger">
                            @if ($errors->has('current_password_1'))
                                <strong>{{ $errors->first('current_password_1') }}</strong>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email"
                           class="col-sm-4 col-lg-3 form-control-label">New Password <span class="valid_field">*</span>
                    </label>
                    <div class="col-sm-8 col-lg-9 form-control-label">
                         <input type="password" name="password" class="form-control" id="password">
                            <span class="error text-danger">
                                @if ($errors->has('password'))
                                    <strong>{{ $errors->first('password') }}</strong>
                                @endif
                            </span>
                    </div>
                </div> 
                <div class="form-group row">
                    <label for="password"
                           class="col-sm-4 col-lg-3 form-control-label">Confirm New Password <span class="valid_field">*</span>
                    </label>
                    <div class="col-sm-8 col-lg-9 form-control-label">
                        <input type="password" name="password_confirmation" class="form-control">
                        <span class="error text-danger">
                            @if ($errors->has('password_confirmation'))
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            @endif
                        </span>
                    </div>
                </div> 
                <div class="form-group row m-t-md">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
                                &#xe31b;</i> Change</button>
                        <a href="{{route('adminHome')}}"
                           class="btn btn-default m-t"><i class="material-icons">
                                &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                    </div>
                </div> 
                {{Form::close()}}
            </div>
        </div>
    </div>
<script type="text/javascript">
$('input').keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
</script>
@endsection