@extends('dashboard.layouts.master')
@section('title','Show Product Category')
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
         <h3><i class="btn btn-sm show-eyes list show-icon" style="pointer-events: none;"></i> Show Product Category</h3>
         <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <span>PLP Master Data</span> /
            <span>PLP Categories</span> /
            <a href="{{ route('product.categories') }}">Product Categories</a> /
            <span>Show Product Category</span>  
         </small>
      </div>
      <div class="box-tool">
         <ul class="nav">
            <li class="nav-item inline">
               <a class="nav-link" href="{{route("product.categories")}}">
               <!-- <i class="material-icons md-18">×</i> -->
               </a>
            </li>
         </ul>
      </div>
      <div class="box-body">
         <form>  
         <!-- Tabs -->
         <section id="tabs">
            <div class="container">
               <div class="row">
                  <div class="col-xs-12 "> 
                     <nav>
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                           <a class="nav-item nav-link active" id="General" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>
                           <a class="nav-item nav-link" id="SEO" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">SEO</a> 
                        </div>
                     </nav>
                     <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
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
                                             <select class="selectpicker" name="primary_category_id" disabled id="primary_category_id" data-live-search="true"> 
                                                @if(count($primary_categories) > 0)
                                                   @foreach($primary_categories as $primary_category)
                                                      <option value="{{$primary_category->id}}" @if($primary_category->id == $category->primary_category_id) selected @endif>{{$primary_category->category_name}}</option>
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
                                             <select class="selectpicker" name="subcategory_id" disabled id="subcategory_id" data-live-search="true"> 
                                                @if(count($subcategories) > 0)
                                                   @foreach($subcategories as $subcategory)
                                                      <option value="{{$subcategory->id}}" @if($subcategory->id == $category->subcategory_id) selected @endif >{{$subcategory->artical_number}} {{$subcategory->subcategory}}</option>
                                                   @endforeach
                                                @endif
                                             </select> 
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="card">
                                    <div class="card-header">
                                       General
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12">
                                             <label for="artical_number"
                                                class="form-control-label">Artical Number
                                             </label> 
                                             <input type="text" name="artical_number"  value="{{$category->artical_number}}" class="form-control" disabled> 
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="product_category"
                                                class="form-control-label">Product category  
                                             </label> 
                                             <input type="text" name="product_category" value="{{$category->product_category}}" class="form-control" disabled>  
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="url_key"
                                                class="form-control-label">URL key  
                                             </label> 
                                             <input type="text" name="url_key" id="url_key" value="{{$category->url_key}}" class="form-control" disabled> 
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="full_url_key"
                                                class="form-control-label">Full URL key  
                                             </label> 
                                             <input type="text" name="full_url_key" id="full_url_key"  value="{{$category->full_url_key}}" class="form-control" disabled>  
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="position"
                                                class="form-control-label">Position 
                                             </label> 
                                             <input type="number" name="position" value="{{$category->position}}" class="form-control" disabled> 
                                          </div> 
                                          <div class="col-sm-12">
                                             <label for="position"
                                                class="form-control-label">Status
                                             </label>  
                                                <select class="selectpicker" name="status" id="status" disabled data-live-search="false">  
                                                      <option value="1" @if($category->status == 1)selected @endif>Active</option>
                                                      <option value="0" @if($category->status == 0)selected @endif>Inactive</option> 
                                             </select>   
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-6">
                                 <div class="card">
                                    <div class="card-header">
                                       Meta
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12">
                                             <label for="meta_title" class="form-control-label">Meta title
                                             </label> 
                                             <input type="text" name="meta_title" value="{{$category->meta_title}}" class="form-control" disabled> 
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="meta_description"
                                                class="form-control-label">Meta description
                                             </label>
                                             <textarea class="form-control" name="meta_description" rows="5" disabled>{{$category->meta_description}}</textarea>  
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div> 
                           <div class="row">
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Description
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12"> 
                                             <textarea class="form-control" id="page_content" name="description" autofocus  disabled>{{ isset($category->description)?urldecode($category->description):old('content') }}</textarea> 
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>  
                           <div class="row">
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Design
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12">  
                                             <label for="photo_file"
                                             class="col-sm-2 form-control-label">Icon</label>
                                          <div class="col-sm-10">
                                             @if($category->icon != "")
                                             <div class="row">
                                                <div class="col-sm-12 images">
                                                   <div id="user_photo" class="col-sm-4 box p-a-xs">
                                                      <a target="_blank"
                                                         href="{{ asset('uploads/product_categories/'.$category->icon) }}"><img
                                                         src="{{ asset('uploads/product_categories/'.$category->icon) }}"
                                                         class="img-responsive">
                                                      </a> 
                                                   </div> 
                                                </div>
                                             </div>
                                             @endif 
                                          </div>
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>  
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="SEO">
                           <div class="row">
                              <div class="col-sm-12">
                                 <div class="card">
                                    <div class="card-header">
                                       Texts
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-3"> 
                                              <label for="seo_headline_one"
                                                class="form-control-label">SEO headline one
                                             </label> 
                                          </div>
                                          <div class="col-sm-9">   
                                             <input class="form-control" name="seo_headline_one" value="@if(isset($seo->seo_headline_one)){{$seo->seo_headline_one}}@endif" disabled> 
                                          </div> 
                                       </div>
                                       <br>
                                       <div class="row">
                                          <div class="col-sm-3"> 
                                              <label for="seo_headline_two"
                                                class="form-control-label">SEO description one
                                             </label> 
                                          </div>
                                          <div class="col-sm-9">     
                                             <textarea class="form-control" rows="15" name="seo_description_one" disabled>@if(isset($seo->seo_description_one)){{$seo->seo_description_one}}@endif</textarea> 
                                          </div> 
                                       </div>
                                        <br>
                                       <div class="row">
                                          <div class="col-sm-3"> 
                                              <label for="seo_headline_two"
                                                class="form-control-label">SEO headline two
                                             </label> 
                                          </div>
                                          <div class="col-sm-9">   
                                             <input class="form-control" name="seo_headline_two" value="@if(isset($seo->seo_headline_two)){{$seo->seo_headline_two}}@endif" disabled>  
                                          </div> 
                                       </div>
                                       <br>
                                       <div class="row">
                                          <div class="col-sm-3"> 
                                              <label for="seo_description_two"
                                                class="form-control-label">SEO description two
                                             </label> 
                                          </div>
                                          <div class="col-sm-9">     
                                             <textarea class="form-control" rows="15" name="seo_description_two" disabled>@if(isset($seo->seo_description_two)){{$seo->seo_description_two}}@endif</textarea> 
                                          </div> 
                                       </div>
                                       <br>
                                       <div class="row">
                                          <div class="col-sm-3"> 
                                              <label for="seo_headline_three"
                                                class="form-control-label">SEO headline three
                                             </label> 
                                          </div>
                                          <div class="col-sm-9">   
                                             <input class="form-control" name="seo_headline_three" value="@if(isset($seo->seo_headline_three)){{$seo->seo_headline_three}}@endif" disabled> 
                                          </div> 
                                       </div>
                                       <br>
                                       <div class="row">
                                          <div class="col-sm-3"> 
                                              <label for="seo_description_three"
                                                class="form-control-label">SEO description three
                                             </label> 
                                          </div>
                                          <div class="col-sm-9">     
                                             <textarea class="form-control" name="seo_description_three" rows="15" disabled>@if(isset($seo->seo_description_three)){{$seo->seo_description_three}}@endif</textarea> 
                                          </div> 
                                       </div>
                                       <br>
                                       <div class="row">
                                          <div class="col-sm-3"> 
                                              <label for="seo_description_other"
                                                class="form-control-label">SEO description other
                                             </label> 
                                          </div>
                                          <div class="col-sm-9">     
                                             <textarea class="form-control" name="seo_description_other" rows="15" disabled>@if(isset($seo->seo_description_other)){{$seo->seo_description_other}}@endif</textarea>
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div> 
                        </div>
                     </div>
                     <div class="form-group row m-t-md">
                        <div class="col-sm-12"> 
                           <a href="{{route('product.categories')}}"
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

<script src="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.js") }}"></script>
    <script src="{{ asset("assets/dashboard/js/summernote/dist/summernote.js") }}"></script>
    <script src= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


    <script>
        
        // update progress bar
        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                $('progress').attr({value: e.loaded, max: e.total});
                // reset progress on complete
                if (e.loaded == e.total) {
                    $('progress').attr('value', '0.0');
                }
            }
        }
    </script>
<script> 
         /**************************************/   

function InsertHTML() {
    var editor = CKEDITOR.instances.editor1;
    var value = document.getElementById( 'htmlArea' ).value;

    if ( editor.mode == 'wysiwyg' )
    {
        editor.insertHtml( value );
    }
    else
        alert( 'You must be in WYSIWYG mode!' );
}

function InsertText() {
    var editor = CKEDITOR.instances.editor1;
    var value = document.getElementById( 'txtArea' ).value;

    if ( editor.mode == 'wysiwyg' )
    {
        editor.insertText( value );
    }
    else
        alert( 'You must be in WYSIWYG mode!' );
}

function SetContents() {
    var editor = CKEDITOR.instances.editor1;
    var value = document.getElementById( 'htmlArea' ).value;

    editor.setData( value );
}

function GetContents() {
    var editor = CKEDITOR.instances.editor1;
    alert( editor.getData() );
}

function ExecuteCommand( commandName ) {
    var editor = CKEDITOR.instances.editor1;

    if ( editor.mode == 'wysiwyg' )
    {
        editor.execCommand( commandName );
    }
    else
        alert( 'You must be in WYSIWYG mode!' );
}

function CheckDirty() {
    var editor = CKEDITOR.instances.editor1;
    alert( editor.checkDirty() );
}

function ResetDirty() {
    var editor = CKEDITOR.instances.editor1;
    editor.resetDirty();
    alert( 'The "IsDirty" status has been reset' );
}

function Focus() {
    CKEDITOR.instances.editor1.focus();
}

function onFocus() {
    document.getElementById( 'eMessage' ).innerHTML = '<b>' + this.name + ' is focused </b>';
}

function onBlur() {
    document.getElementById( 'eMessage' ).innerHTML = this.name + ' lost focus';
}
        
    CKEDITOR.replace('page_content', {
        on: {
            pluginsLoaded: function(evt) {
                var doc = CKEDITOR.document,
                    ed = evt.editor;
                if (!ed.getCommand('bold')) doc.getById('exec-bold').hide();
                if (!ed.getCommand('link')) doc.getById('exec-link').hide();
            }
        }
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