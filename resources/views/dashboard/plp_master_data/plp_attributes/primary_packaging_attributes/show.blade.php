@extends('dashboard.layouts.master')
@section('title','View Primary Packaging Attributes')
@section('content')
<?php
   $allowed_permissions = @Helper::GetRolePermission(Auth::id(),4); 
   ?>
<style type="text/css"> 
   .dropdown-toggle::after { 
   display: none;
}
</style>
@if(isset($allowed_permissions) && $allowed_permissions->update == 1) 
<div class="padding edit-package edit-user">
<div class="box">
   <div class="box-header dker">
      <h3><i class="btn btn-sm show-eyes list show-icon" style="pointer-events: none;"></i>  View Primary Packaging Attributes</h3>
      <small>
      <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
      <span>PLP Master Data</span> /
      <span>PLP Attributes</span> /
      <a href="{{ route('primary.packaging.attributes') }}">Primary Packaging Attributes</a> /
      <span>View Primary Packaging Attributes</span>  
      </small>
   </div> 
   <div class="box-body">
      {{Form::open(['route'=>['primary.packaging.attributes.update'],'method'=>'POST'])}}  
      <section id="tabs">
         <div class="container">
            <div class="row">
               <div class="col-xs-12 "> 
                     <div class="form-group row">
                        <div class="col-sm-12">
                           <div class="row">
                              <div class="col-sm-6">
                                 <label for="category_name"
                                    class="form-control-label">Primary Packaging Attribute
                                 </label> 
                                 <input type="text" name="primary_packaging_attribute" value="{{$primary_packaging_attribute->primary_packaging_attribute}}" class="form-control" disabled>  
                              </div>
                              <div class="col-sm-6">
                                 <label for="position"
                                    class="form-control-label">Position 
                                 </label> 
                                 <input type="number" name="position" value="{{$primary_packaging_attribute->position}}" class="form-control" disabled> 
                              </div>
                              <div class="col-sm-6">
                              <label for="Status"
                                 class="form-control-label">Status
                              </label>  
                                 <select class="selectpicker" name="status" id="status" data-live-search="false" disabled>  
                                       <option value="1" @if($primary_packaging_attribute->status == 1)selected @endif>Active</option>
                                       <option value="0" @if($primary_packaging_attribute->status == 0)selected @endif>Inactive</option> 
                              </select>  
                           </div>
                           <div class="col-sm-6">
                                 <label for="Primary_Packaging"
                                    class="form-control-label">Primary Packaging
                                 </label> 
                                 <br>
                                 <select class="tag_selection1" multiple="multiple" disabled name="primary_packaging[]"  title="Please select primary packaging" >
                                    @if(count($all_primary_packaging) > 0 )
                                    @foreach($all_primary_packaging as $key => $primary)  
                                       @if(in_array($primary['id'], $selected_primary_packaging)) 
                                          <option value="{{$primary['id']}}" selected >1{{$primary['text']}}</option> 
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
                           <a href="{{route('primary.packaging.attributes')}}"
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
   var all_primary_packaging = '<?php echo json_encode($all_primary_packaging); ?>'; 
   $('.tag_selection1').select2({
       data: JSON.parse(all_primary_packaging),
       tags: false,
       maximumSelectionLength: -1,
       tokenSeparators: [',', ' '],
       placeholder: "  Select primary packaging", 
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