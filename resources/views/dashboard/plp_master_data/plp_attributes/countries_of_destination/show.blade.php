@extends('dashboard.layouts.master')
@section('title','View Countries Of Destination')
@section('content')
<?php
   $allowed_permissions = @Helper::GetRolePermission(Auth::id(),4); 
   ?>
<style type="text/css"> 
   .dropdown-toggle::after { 
   display: none;
}
span.select2.select2-container.select2-container--default {
    width: 100%!important;
}
</style>
@if(isset($allowed_permissions) && $allowed_permissions->update == 1) 
<div class="padding edit-package edit-user">
<div class="box">
   <div class="box-header dker">
      <h3><i class="btn btn-sm show-eyes list show-icon" style="pointer-events: none;"></i>  View Countries Of Destination</h3>
      <small>
      <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
      <span>PLP Master Data</span> /
      <span>PLP Attributes</span> /
      <a href="{{ route('countries.of.destination') }}">Countries Of Destination</a> /
      <span>View Countries Of Destination</span>  
      </small>
   </div> 
   <div class="box-body">
      {{Form::open(['route'=>['countries.of.destination.update'],'method'=>'POST'])}}  
      <section id="tabs">
         <div class="container">
            <div class="row">
               <div class="col-xs-12 ">
                  <input type="hidden" name="encode_id" value="{{$encode_id}}">   
                     <div class="form-group row">
                        <div class="col-sm-12">
                           <div class="row">
                              <div class="col-sm-6">
                                 <label for="category_name"
                                    class="form-control-label">Country short 
                                 </label> 
                                 <input type="text" name="country_short" value="{{$countries_of_destination->country_short}}" class="form-control" disabled>  
                              </div>

                              <div class="col-sm-6">
                                 <label for="category_name"
                                    class="form-control-label">Country 
                                 </label> 
                                 <input type="text" name="country" value="{{$countries_of_destination->country}}" class="form-control" disabled>  
                              </div>
                              <div class="col-sm-6">
                                 <label for="position"
                                    class="form-control-label">Position 
                                 </label> 
                                 <input type="number" name="position" value="{{$countries_of_destination->position}}" class="form-control" disabled> 
                              </div>
                              <div class="col-sm-6">
                              <label for="Status"
                                 class="form-control-label">Status
                              </label>  
                                 <select class="selectpicker" name="status" id="status" data-live-search="false" disabled>  
                                       <option value="1" @if($countries_of_destination->status == 1)selected @endif>Active</option>
                                       <option value="0" @if($countries_of_destination->status == 0)selected @endif>Inactive</option> 
                              </select>  
                           </div>
                           </div>
                           <br>
                           <div class="row"> 
                              <div class="col-sm-12">
                                 <label for="product_category"
                                    class="form-control-label">Subcategories  
                                 </label> 
                              <br>
                                 <select class="tag_selection1" multiple="multiple" name="product_subcategories[]" disabled>
                                    @if(count($CountriesOfDestinationSubcategory) > 0 )
                                    @foreach($CountriesOfDestinationSubcategory as $key => $subcategory)
                                       @if(isset($subcategory['subcategory']->subcategory))
                                          <option value="{{$subcategory->product_subcategory_id}}" selected >{{$subcategory['subcategory']->artical_number}} {{$subcategory['subcategory']->subcategory}}</option> 
                                       @endif 
                                    @endforeach 
                                    @endif
                                 </select> 
                              </div>
                           </div>
                           <br>
                           <div class="row"> 
                              <div class="col-sm-12">
                                 <label for="product_category"
                                    class="form-control-label">Product categories   
                                 </label> 
                                  <br>
                                 <select class="tag_selection2" multiple="multiple" name="product_categories[]" disabled>
                                    @if(count($CountriesOfDestinationProductCategory) > 0 )
                                    @foreach($CountriesOfDestinationProductCategory as $key => $product_category)
                                       @if(isset($product_category['productcategory']->product_category))
                                          <option value="{{$product_category->product_category_id}}" selected >{{$product_category['productcategory']->artical_number}} {{$product_category['productcategory']->product_category}} </option> 
                                       @endif 
                                    @endforeach 
                                    @endif
                                 </select>  
                              </div>  
                           </div>   
                        </div>
                     </div>
                     <div class="form-group row m-t-md">
                        <div class="col-sm-12"> 
                           <a href="{{route('countries.of.destination')}}"
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
   integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
   crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
   integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script>
   var all_product_subcategories = '<?php echo json_encode($all_product_subcategories); ?>'; 
   $('.tag_selection1').select2({
       data: JSON.parse(all_product_subcategories),
       tags: false,
       maximumSelectionLength: -1,
       tokenSeparators: [',', ' '],
       placeholder: "  Select subcategories", 
   });  

</script>
<script> 
   var all_product_categories = '<?php echo json_encode($all_product_categories); ?>'; 
   $('.tag_selection2').select2({
       data: JSON.parse(all_product_categories),
       tags: false,
       maximumSelectionLength: -1,
       tokenSeparators: [',', ' '],  
       placeholder: "  Select product categories", 
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