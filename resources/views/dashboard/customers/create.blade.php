@extends('dashboard.layouts.master')
@section('title','Create Customer')
@section('content')

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),7); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1) 
<div class="padding edit-package edit-user">
   <div class="box">
      <div class="box-header dker">
         <h3><i class="fa fa-plus"></i> Create Customer</h3>
         <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> / 
            <a href="{{ route('customers') }}">Customers</a> /
            <span>Create Customer</span> 
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
         {{Form::open(['route'=>['customers.store'],'method'=>'POST', 'files' => true])}} 
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
                  <label for="phone_number"
                     class="form-control-label">Phone Number <span class="valid_field">*</span>
                  </label>
                  <input type="text" name="phone_number" value="{{old('phone_number')}}" class="form-control">
                  <span class="help-block">
                  @if(!empty(@$errors) && @$errors->has('phone_number'))
                  <span  style="color: red;" class='validate'>{{ $errors->first('phone_number') }}</span>
                  @endif
                  </span>
               </div>  
            </div>
            <div class="form-group row">
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
            <div class="col-sm-6">
               <label for="company_size"
                  class="form-control-label">Company size
               </label>
               <select class="selectpicker" name="company_size" data-live-search="true" title="Please select company size">  
                  <option value="small" @if(old('company_size') == 'small') selected @endif>Small Size (0-49 Employees)</option> 
                  <option value="medium">Medium Size (50-199 Employees)</option> 
                  <option value="large">Large Size (200-999 Employees)</option> 
                  <option value="big">Big Enterprise (1000-unlimited Employees)</option>  
                  </select>
                  <span class="help-block">
                  @if(!empty(@$errors) && @$errors->has('company_size'))
                  <span  style="color: red;" class='validate'>{{ $errors->first('company_size') }}</span>
                  @endif
                  </span>
            </div>
         </div>
         <div class="form-group row">
            <div class="col-sm-6">
               <label for="company_address"
                  class="form-control-label">Company Address <span class="valid_field">*</span>
               </label>
               <textarea name="company_address" class="form-control has-value" rows="2">{{old('company_address')}}</textarea>
                  <span class="help-block">
                  @if(!empty(@$errors) && @$errors->has('company_address'))
                  <span  style="color: red;" class='validate'>{{ $errors->first('company_address') }}</span>
                  @endif
                  </span>
            </div>
            <div class="col-sm-6">
               <label for="zip"
                  class="form-control-label">Zip <span class="valid_field">*</span>
               </label>
               {!! Form::text('zip',old('zip'), array('placeholder' => '','class' => 'form-control','id'=>'zip')) !!}
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('zip'))
               <span  style="color: red;" class='validate'>{{ $errors->first('zip') }}</span>
               @endif
               </span> 
            </div> 
            </div> 
            <div class="form-group row"> 
            <div class="col-sm-6">
               <label for="country"
                  class="form-control-label">Country <span class="valid_field">*</span>
               </label>
               {!! Form::text('country',old('country'), array('placeholder' => '','class' => 'form-control','id'=>'country')) !!}
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('country'))
               <span  style="color: red;" class='validate'>{{ $errors->first('country') }}</span>
               @endif
               </span> 
            </div>
            <div class="col-sm-6">
               <label for="photo_file"
               class="form-control-label">Profile Picture <span class="valid_field">*</span></label>
             
               {!! Form::file('profile_picture', array('class' => 'form-control','id'=>'profile_picture','accept'=>'image/*')) !!}
               <small>
               <i class="material-icons">&#xe8fd;</i>
               {!!  __('backend.imagesTypes') !!}
               </small>
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('profile_picture'))
               <span  style="color: red;" class='validate'>{{ $errors->first('profile_picture') }}</span>
               @endif
               </span> 
            </div> 
            </div>  
            <div class="form-group row"> 
            <div class="col-sm-6">
               <label for="vat_number"
                  class="form-control-label">VAT Number <span class="valid_field">*</span>
               </label>
               {!! Form::text('vat_number',old('vat_number'), array('placeholder' => '','class' => 'form-control','id'=>'vat_number')) !!}
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('vat_number'))
               <span  style="color: red;" class='validate'>{{ $errors->first('vat_number') }}</span>
               @endif
               </span> 
            </div>
            <div class="col-sm-6">
               <label for="photo_file"
               class="form-control-label">Product Category<span class="valid_field">*</span></label>
               <select class="selectpicker" name="product_category" data-live-search="true" title="Please select product category"> 
                  @if(count($product_categories) > 0)
                  @foreach($product_categories as $category)
                  <option value="{{$category->id}}" @if(old('product_category') == $category->id) selected @endif>{{$category->product_category}}</option> 
                  @endforeach
                  @endif   
                  </select>
               <span class="help-block">
               @if(!empty(@$errors) && @$errors->has('product_category'))
               <span  style="color: red;" class='validate'>{{ $errors->first('product_category') }}</span>
               @endif
               </span> 
            </div> 
            </div>  
         <div class="form-group row m-t-md">
            <div class="col-sm-12">
               <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
               &#xe31b;</i> {!! __('backend.save') !!}</button>
               <a href="{{route('customers')}}"
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