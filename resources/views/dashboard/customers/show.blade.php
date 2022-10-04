@extends('dashboard.layouts.master')
@section('title','View User')
@section('content')

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),7); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1) 
<style type="text/css">
   input::-webkit-outer-spin-button,
   input::-webkit-inner-spin-button {
     -webkit-appearance: none;
     margin: 0;
   }
   .dropdown-toggle::after { 
   display: none;
}
</style>
    <div class="padding edit-package edit-user">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> View User</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <span>Access Control</span> /
                    <a href="{{ route('users') }}">Users</a> /
                    <span>View User</span> 
                    <!-- <a href="javascript:void(0)">Edit Profile</a> -->
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("users")}}">
                            <!-- <i class="material-icons md-18">×</i> -->
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body">
               <form>
                <input type="hidden" name="encode_id" value="{{$encode_id}}">
                <div class="form-group row"> 
                    <div class="col-sm-6">
                        <label for="name"
                           class="form-control-label">Name
                    </label>
                        {!! Form::text('name',$users->name, array('placeholder' => '','class' => 'form-control','disabled'=>'disabled')) !!} 
                    </div>
                    
                    
                    <div class="col-sm-6">
                        <label for="email"
                           class="form-control-label">Email
                        </label>
                        {!! Form::email('email',$users->email, array('placeholder' => '','class' => 'form-control','disabled'=>'disabled')) !!} 
                    </div>
                    
                </div> 
                <div class="form-group row">
                   
                    <div class="col-sm-6">
                         <label for="mobile_number"
                           class="form-control-label">Mobile Number
                    </label>
                        <input type="text" name="mobile_number" class="form-control" disabled value="{{$users->mobile_number}}"> 
                    </div> 
               
                </div>
                <br>
                <div class="form-group row">
                   
                    <div class="col-sm-2">
                         <label for="roles"
                           class="form-control-label">Roles
                    </label>
                </div>
                <div class="col-sm-10">
                       

<select class="selectpicker" name="roles[]" multiple data-live-search="false" disabled>
    @foreach($roles as $key => $role)
  <option value="{{$role->id}}" @if(in_array($role->id, $selected_roles)) selected="selected" @endif>{{$role->name}}</option> 
  @endforeach
</select>
                         
                    </div> 
               
                </div>

                <div class="form-group row">
                    <label for="photo_file"
                           class="col-sm-2 form-control-label">{!!  __('backend.topicPhoto') !!}</label>
                    <div class="col-sm-10">
                        @if($users->image!="")
                            <div class="row">
                                <div class="col-sm-12 images">
                                    <div id="user_photo" class="col-sm-4 box p-a-xs">
                                        <a target="_blank"
                                           href="{{ asset('uploads/users/'.$users->image) }}"><img
                                                src="{{ asset('uploads/users/'.$users->image) }}"
                                                class="img-responsive">
                                        </a>
                                        <br> 
                                    </div>  
                                </div>
                            </div>
                        @endif 
                    </div>
                </div> 
                <div class="form-group row m-t-md">
                    <div class="col-sm-12"> 
                        <a href="{{route('users')}}"
                           class="btn btn-default m-t"><i class="material-icons">
                                &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                    </div>
                </div> 
            </form>
            </div>
        </div>
    </div>

 @else

<input type="hidden" value="{{route('adminHome')}}" id="redirect_url">
<script type="text/javascript"> 
   var redirect_url = $('#redirect_url').val();
   window.location.replace(redirect_url); 
</script>

@endif   
@endsection