@extends('dashboard.layouts.master')
@section('title','Create Primary Packaging Attributes')
@section('content')
<?php
   $allowed_permissions = @Helper::GetRolePermission(Auth::id(),4); 
   ?>
<style type="text/css">
   .tag_selection1 {
   width: 100%!important;  
   }
   .tag_selection2 {
   width: 100%!important;  
   }
   .tag_selection2-container--default .tag_selection2-selection--multiple .tag_selection2-selection__choice {
   background-color: #00ed3d!important; 
   }
   .tag_selection1-container--default .tag_selection1-selection--multiple .tag_selection1-selection__choice {
   background-color: #00ed3d!important; 
   }
   .select2-search__field{
      width: 100%!important;
   }
   span.select2.select2-container.select2-container--default {
    width: 100%!important;
}
</style>
@if(isset($allowed_permissions) && $allowed_permissions->create == 1) 
<div class="padding edit-package edit-user">
<div class="box">
   <div class="box-header dker">
      <h3><i class="fa fa-plus"></i> Create Primary Packaging Attributes</h3>
      <small>
      <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
      <span>PLP Master Data</span> /
      <span>PLP Attributes</span> /
      <a href="{{ route('primary.packaging.attributes') }}">Primary Packaging Attributes</a> /
      <span>Create Primary Packaging Attributes</span>  
      </small>
   </div> 
   <div class="box-body">
      {{Form::open(['route'=>['primary.packaging.attributes.store'],'method'=>'POST'])}}  
      <section id="tabs">
         <div class="container">
            <div class="row">
               <div class="col-xs-12 ">   
                     <div class="form-group row">
                        <div class="col-sm-12">
                           <div class="row">
                              <div class="col-sm-6">
                                 <label for="category_name"
                                    class="form-control-label">Primary Packaging Attribute <span class="valid_field">*</span>
                                 </label> 
                                 <input type="text" name="primary_packaging_attribute" value="{{old('primary_packaging_attribute')}}" class="form-control"> 
                                 <span class="help-block">
                                 @if(!empty(@$errors) && @$errors->has('primary_packaging_attribute'))
                                 <span  style="color: red;" class='validate'>{{ $errors->first('primary_packaging_attribute') }}</span>
                                 @endif
                                 </span> 
                              </div>
                              <div class="col-sm-6">
                                 <label for="position"
                                    class="form-control-label">Position <span class="valid_field">*</span>
                                 </label> 
                                 <input type="number" name="position" min="1"  onkeydown="javascript: return event.keyCode == 69 ? false : true" value="{{old('position')}}" class="form-control">
                                 <span class="help-block">
                                 @if(!empty(@$errors) && @$errors->has('position'))
                                 <span  style="color: red;" class='validate'>{{ $errors->first('position') }}</span>
                                 @endif
                                 </span>
                              </div>
                              <div class="col-sm-6">
                                 <label for="primary_packaging"
                                    class="form-control-label">Primary Packaging <span class="valid_field">*</span>
                                 </label> 
                                 <br>
                                 <select class="tag_selection1" multiple="multiple" name="primary_packaging[]"  title="Please select primary packaging" > 
                                 </select> 
                                    
                                 <span class="help-block">
                                 @if(!empty(@$errors) && @$errors->has('primary_packaging'))
                                 <span  style="color: red;" class='validate'>{{ $errors->first('primary_packaging') }}</span>
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