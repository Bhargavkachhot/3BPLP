@extends('dashboard.layouts.master')
@section('title','Show Example Product')
@section('content')

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),1); 
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
         <h3><i class="btn btn-sm show-eyes list show-icon" style="pointer-events: none;"></i> Show Example Product</h3>
         <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <span>PLP Master Data</span> /
            <span>PLP Categories</span> /
            <a href="{{ route('example.products') }}">Example Product</a> /
            <span>Show Example Product</span>  
         </small>
      </div>
      <div class="box-tool">
         <ul class="nav">
            <li class="nav-item inline">
               <a class="nav-link" href="{{route("example.products")}}"> 
               </a>
            </li>
         </ul>
      </div>
      <div class="box-body">
         <form>
         <section id="tabs">
            <div class="container">
               <div class="row">
                  <div class="col-xs-12 ">  
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="General">
                           <div class="form-group row">
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Primary Category 
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12" id="primary_category_selection">  
                                             <select class="selectpicker" name="primary_category_id" id="primary_category_id" data-live-search="true" disabled> 
                                                @if(count($primary_categories) > 0)
                                                   @foreach($primary_categories as $primary_category)
                                                      <option value="{{$primary_category->id}}" @if($primary_category->id == $example_product->primary_category_id) selected @endif>{{$primary_category->category_name}}</option>
                                                   @endforeach
                                                @endif
                                             </select>    
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Subcategory 
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12" id="subcategory_selection">  
                                             <select class="selectpicker" name="subcategory_id" id="subcategory_id" data-live-search="true" disabled> 
                                                @if(count($subcategories) > 0)
                                                   @foreach($subcategories as $subcategory)
                                                      <option value="{{$subcategory->id}}" @if($subcategory->id == $example_product->subcategory_id) selected @endif >{{$subcategory->artical_number}} {{$subcategory->subcategory}}</option>
                                                   @endforeach
                                                @endif
                                             </select> 
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Product category 
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12" >  
                                             <select class="selectpicker" name="product_category_id" id="product_category_id" data-live-search="true" disabled> 
                                                @if(count($product_categories) > 0)
                                                   @foreach($product_categories as $product_category)
                                                      <option value="{{$product_category->id}}" @if($product_category->id == $example_product->product_category_id) selected @endif >{{$product_category->artical_number}} {{$product_category->product_category}}</option>
                                                   @endforeach
                                                @endif
                                             </select>  
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
                                                class="form-control-label">Example product 
                                             </label> 
                                             <input type="text" name="example_product" value="{{$example_product->example_product}}" class="form-control" disabled>   
                                          </div> 
                                          <div class="col-sm-12">
                                             <label for="position"
                                                class="form-control-label">Position 
                                             </label> 
                                             <input type="number" name="position" value="{{$example_product->position}}" class="form-control" disabled> 
                                          </div> 
                                          <div class="col-sm-12">
                                             <label for="position"
                                                class="form-control-label">Status
                                             </label>  
                                                <select class="selectpicker" name="status" id="status" disabled data-live-search="false">  
                                                      <option value="1" @if($example_product->status == 1)selected @endif>Active</option>
                                                      <option value="0" @if($example_product->status == 0)selected @endif>Inactive</option> 
                                             </select>   
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div> 
                           </div>   
                        </div>   
                     <div class="form-group row m-t-md">
                        <div class="col-sm-12"> 
                           <a href="{{route('example.products')}}"
                              class="btn btn-default m-t"><i class="material-icons">
                           &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                        </div>  
                     </div>
                  </div>
               </div>
            </div>
         </section>
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