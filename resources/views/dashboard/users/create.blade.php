@extends('dashboard.layouts.master')
@section('title','Create User')
@section('content')

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),2); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1) 
<div class="padding edit-package edit-user">
   <div class="box">
      <div class="box-header dker">
         <h3><i class="fa fa-plus"></i> Create User</h3>
         <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <span>Access Control</span> /
            <a href="{{ route('users') }}">Users</a> /
            <span>Create User</span> 
            <!-- <a href="javascript:void(0)">Edit User</a> -->
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
         {{Form::open(['route'=>['user.store'],'method'=>'POST', 'files' => true])}} 
         <div class="form-group row">
            <div class="col-sm-6">
               <label for="name"
                  class="form-control-label">Name <span class="valid_field">*</span>
               </label>
               {!! Form::text('name',old('name'), array('placeholder' => '','class' => 'form-control','id'=>'name')) !!}
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('name'))
               <span  style="color: red;" class='validate'>{{ $errors->first('name') }}</span>
               @endif
               </span> 
            </div>
            <div class="col-sm-6">
               <label for="email"
                  class="form-control-label">Email <span class="valid_field">*</span>
               </label>
               {!! Form::email('email',old('email'), array('placeholder' => '','class' => 'form-control','id'=>'email')) !!}
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('email'))
               <span  style="color: red;" class='validate'>{{ $errors->first('email') }}</span>
               @endif
               </span>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-6">
               <label for="mobile_number"
                  class="form-control-label">Mobile Number <span class="valid_field">*</span>
               </label>
               <input type="text" name="mobile_number" value="{{old('mobile_number')}}" class="form-control">
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('mobile_number'))
               <span  style="color: red;" class='validate'>{{ $errors->first('mobile_number') }}</span>
               @endif
               </span>
            </div>
            <div class="col-sm-6">
               <label for="password"
                  class="form-control-label">Password <span class="valid_field">*</span>
               </label>
               <input type="password" name="password"  class="form-control" autocomplete="new-password"> 
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('password'))
               <span  style="color: red;" class='validate'>{{ $errors->first('password') }}</span>
               @endif
               </span>
            </div>
         </div>
         <br>
         <div class="form-group row">
            <div class="col-sm-2">
               <label for="roles"
                  class="form-control-label">Roles <span class="valid_field">*</span>
               </label>
            </div>
            <div class="col-sm-10">
               <select class="selectpicker" name="roles" data-live-search="true" title="Please select role"> 
               @foreach($roles as $key => $role)
               <option value="{{$role->id}}">{{$role->name}}</option> 
               @endforeach
               </select>
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('roles'))
               <span  style="color: red;" class='validate'>{{ $errors->first('roles') }}</span>
               @endif
               </span>
            </div>
         </div>
         <br>
         <div class="form-group row">
            <label for="photo_file"
               class="col-sm-2 form-control-label">Profile Picture <span class="valid_field">*</span></label>
            <div class="col-sm-10"> 
               {!! Form::file('image', array('class' => 'form-control','id'=>'image','accept'=>'image/*')) !!}
               <small>
               <i class="material-icons">&#xe8fd;</i>
               {!!  __('backend.imagesTypes') !!}
               </small>
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('image'))
               <span  style="color: red;" class='validate'>{{ $errors->first('image') }}</span>
               @endif
               </span>
            </div>
         </div>
         <div class="form-group row m-t-md">
            <div class="col-sm-12">
               <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
               &#xe31b;</i> {!! __('backend.save') !!}</button>
               <a href="{{route('users')}}"
                  class="btn btn-default m-t"><i class="material-icons">
               &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
            </div>
         </div>
         {{Form::close()}}
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