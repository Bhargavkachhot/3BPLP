@extends('dashboard.layouts.master')
@section('title','Import SKU Packaging')
@section('content')
<!-- @if(count($errors) > 0 && !@$errors->has('file'))
    <div class="row">
        <div class="col-lg-12">
          <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="color: black;">×</button>
              <h4><i class="icon fa fa-ban"></i> Error!</h4>
              @foreach($errors->all() as $error)
              {{ $error }} <br>
              @endforeach      
          </div>
        </div>
    </div>
@endif
<?php
   $allowed_permissions = @Helper::GetRolePermission(Auth::id(),4); 
   ?> 
@if(isset($allowed_permissions) && $allowed_permissions->create == 1) 
<div class="padding edit-package edit-user">
<div class="box">
   <div class="box-header dker">
      <h3><i class="fa fa-upload"  style="pointer-events: none;"></i> Import SKU Packaging</h3>
      <small>
      <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
      <span>PLP Master Data</span> /
      <span>PLP Attributes</span> /
      <a href="{{ route('sku.packaging') }}">SKU Packaging</a> /
      <span>Import SKU Packaging</span>  
      </small>
   </div> 
   <div class="box-body">
      {{Form::open(['route'=>['sku.packaging.import.store'],'method'=>'POST', 'files' => true])}}  
      <section id="tabs">
         <div class="container">
            <div class="row">
               <div class="col-xs-12 ">   
                     <div class="form-group row">
                        <div class="col-sm-12">
                           <div class="row">
                              <div class="col-sm-6">
                                 <label for="category_name"
                                    class="form-control-label">SKU Packaging 
                                 </label> 
                                 <input type="file" name="file"   class="form-control" accept=".xls,.xlsx,.csv" > 
                                 <small>
                                 <i class="material-icons">&#xe8fd;</i>
                                 {!!  __('backend.importTypes') !!}
                                 </small>
                                 <br>
                                 <span class="help-block">
                                 @if(!empty(@$errors) && @$errors->has('file'))
                                 <span  style="color: red;">{{ $errors->first('file') }}</span>
                                 @endif
                                 </span>
                              </div> 
                           </div>  
                        </div>
                     </div>
                     <div class="form-group row m-t-md">
                        <div class="col-sm-12">
                           <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
                           &#xe31b;</i> Save</button>
                           <a href="{{route('sku.packaging')}}"
                              class="btn btn-default m-t"><i class="material-icons">
                           &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
      </section>
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
@endif -->
@endsection