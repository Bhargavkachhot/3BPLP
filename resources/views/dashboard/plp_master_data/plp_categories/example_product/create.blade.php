@extends('dashboard.layouts.master')
@section('title','Create Example Product')
@section('content')

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),1); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->create == 1) 
<div class="padding edit-package edit-user">
   <div class="box">
      <div class="box-header dker">
         <h3><i class="fa fa-plus"></i> Create Example Product</h3>
         <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <span>PLP Master Data</span> /
            <span>PLP Categories</span> /
            <a href="{{ route('example.products') }}">Example Product</a> /
            <span>Create Example Product</span>  
         </small>
      </div>
      <div class="box-tool">
         <ul class="nav">
            <li class="nav-item inline">
               <a class="nav-link" href="{{route("example.products")}}">
               <!-- <i class="material-icons md-18">×</i> -->
               </a>
            </li>
         </ul>
      </div>
      <div class="box-body">
         {{Form::open(['route'=>['example.products.store'],'method'=>'POST', 'files' => true])}}   
         <!-- Tabs -->
         <section id="tabs">
            <div class="container">
               <div class="row">
                  <div class="col-xs-12 "> 
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="General">
                           <div class="form-group row">
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Primary Category <span class="valid_field">*</span>
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12" id="primary_category_selection">  
                                             <select class="selectpicker" name="primary_category_id" id="primary_category_id" data-live-search="true" title="Select primary category" disabled>  
                                             </select> 
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('primary_category_id'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('primary_category_id') }}</span>
                                             @endif
                                             </span>  
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Subcategory <span class="valid_field">*</span>
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12" id="subcategory_selection">  
                                             <select class="selectpicker" name="subcategory_id" id="subcategory_id" title="Select subcategory" data-live-search="true" disabled>   
                                             </select> 
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('subcategory_id'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('subcategory_id') }}</span>
                                             @endif
                                             </span>  
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Product category <span class="valid_field">*</span>
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12" >  
                                             <select class="selectpicker" name="product_category_id" id="product_category_id" data-live-search="true" title="Select product category">   
                                                @if(count($product_categories) > 0)
                                                   @foreach($product_categories as $product_category)
                                                      <option value="{{$product_category->id}}" >{{$product_category->artical_number}} {{$product_category->product_category}}</option> 
                                                   @endforeach
                                                @endif
                                             </select> 
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('product_category_id'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('product_category_id') }}</span>
                                             @endif
                                             </span>  
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       General
                                    </div>
                                    <div class="card-body">
                                       <div class="row"> 
                                          <div class="col-sm-12">
                                             <label for="example_product"
                                                class="form-control-label">Example product <span class="valid_field">*</span> 
                                             </label> 
                                             <input type="text" name="example_product" maxlength="255"  value="{{old('example_product')}}" class="form-control"> 
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('example_product'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('example_product') }}</span>
                                             @endif
                                             </span> 
                                          </div> 
                                          <div class="col-sm-12">
                                             <label for="position"
                                                class="form-control-label">Position <span class="valid_field">*</span>
                                             </label> 
                                             <input type="number" name="position" min="1"  onkeydown="javascript: return event.keyCode == 69 ? false : true" value="{{old('position')}}"  class="form-control">
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('position'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('position') }}</span>
                                             @endif
                                             </span>
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div> 
                           </div>   
                        </div>   
                     <div class="form-group row m-t-md">
                        <div class="col-sm-12">
                           <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
                           &#xe31b;</i> {!! __('backend.save') !!}</button>
                           <a href="{{route('example.products')}}"
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

<script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script src="{{ asset("assets/dashboard/js/summernote/dist/summernote.js") }}"></script>
    <script src= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


    
<script>
$('form').submit(function () { $('[disabled]').removeAttr('disabled'); })

$(document).on('change', "#product_category_id", function () {  
   var product_category_id = $(this).val();  
   $.ajax({
      type: "POST",
      url: "{{route('example.product.get.data')}}",
      data: {id: product_category_id},
      async: false,
      success: function(response){ 
         console.log(response);
         if(response.primary_category_id == 0 || response.subcategory_id){
            $('.filter-option-inner-inner').text('');
         }else{
            var data = JSON.parse(response); 
            $('#primary_category_id').empty();   
            $("#primary_category_id").html(data.primary_category_html);   
            $("#primary_category_id").selectpicker('refresh'); 
            $('#primary_category_id').selectpicker('val', data.primary_category_id); 
            $('#subcategory_id').empty();   
            $("#subcategory_id").html(data.subcategory_html);   
            $("#subcategory_id").selectpicker('refresh'); 
            $('#subcategory_id').selectpicker('val', data.subcategory_id); 
         }   
      }           
   });    
});  
</script>

@else

<input type="hidden" value="{{route('adminHome')}}" id="redirect_url">
<script type="text/javascript">
   var redirect_url = $('#redirect_url').val();
   window.location.replace(redirect_url); 
</script>

@endif

@endsection