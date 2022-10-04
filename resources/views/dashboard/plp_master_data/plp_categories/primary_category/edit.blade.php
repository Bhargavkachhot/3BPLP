@extends('dashboard.layouts.master')
@section('title','Edit Primary Category')
@section('content')

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),1); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->update == 1)
<style type="text/css">
.box-body {
    background: #fff;
    border-radius: 10px;
    padding: 35px 30px 20px!important;
}
.box-body form{
    padding: 0px!important;
}
</style> 
<div class="padding edit-package edit-user">
   <div class="box">
      <div class="box-header dker">
         <h3><i class="material-icons">&#xe3c9;</i> Edit Primary Category</h3>
         <small>
            <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
            <span>PLP Master Data</span> /
            <span>PLP Categories</span> /
            <a href="{{ route('primary.categories') }}">Primary Categories</a> /
            <span>Edit Primary Category</span>  
         </small>
      </div>
      <div class="box-tool">
         <ul class="nav">
            <li class="nav-item inline">
               <a class="nav-link" href="{{route("primary.categories")}}">
               <!-- <i class="material-icons md-18">×</i> -->
               </a>
            </li>
         </ul>
      </div>
      <div class="box-body">
           
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
                           {{Form::open(['route'=>['primary.categories.update'],'method'=>'POST', 'files' => true])}} 
                            <input type="hidden" name="encode_id" value="{{$encode_id}}">
                           <div class="form-group row">
                              <div class="col-sm-6">
                                 <div class="card">
                                    <div class="card-header">
                                       General
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12">
                                             <label for="category_name"
                                                class="form-control-label">Primary category <span class="valid_field">*</span> 
                                             </label> 
                                             <input type="text" name="category_name" value="{{$category->category_name}}" maxlength="255"  class="form-control"> 
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('category_name'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('category_name') }}</span>
                                             @endif
                                             </span> 
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="url_key"
                                                class="form-control-label">URL key <span class="valid_field">*</span> 
                                             </label> 
                                             <input type="text" name="url_key" id="url_key" maxlength="255"  value="{{$category->url_key}}" class="form-control">
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('url_key'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('url_key') }}</span>
                                             @endif
                                             </span>
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="position"
                                                class="form-control-label">Position <span class="valid_field">*</span>
                                             </label> 
                                             <input type="number" name="position" min="1" id="position" value="{{$category->position}}" class="form-control" onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('position'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('position') }}</span>
                                             @endif
                                             </span>
                                          </div>

                                          <div class="col-sm-12">
                                             <label for="position"
                                                class="form-control-label">Status
                                             </label>  
                                                <select class="selectpicker" name="status" id="status" data-live-search="false">  
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
                                             <label for="meta_title" class="form-control-label">Meta title <span class="valid_field">*</span>
                                             </label> 
                                             <input type="text" name="meta_title" value="{{$category->meta_title}}" class="form-control">
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('meta_title'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('meta_title') }}</span>
                                             @endif
                                             </span> 
                                          </div>
                                          <div class="col-sm-12">
                                             <label for="meta_description"
                                                class="form-control-label">Meta description <span class="valid_field">*</span>
                                             </label>
                                             <textarea class="form-control" name="meta_description" rows="5">{{$category->meta_description}}</textarea> 
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('meta_description'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('meta_description') }}</span>
                                             @endif
                                             </span>
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
                                       Description <span class="valid_field">*</span>
                                    </div>
                                    <div class="card-body">
                                       <div class="row">
                                          <div class="col-sm-12"> 
                                             <textarea class="form-control" id="page_content" name="description" autofocus >{{ isset($category->description)?urldecode($category->description):old('content') }}</textarea>
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('description'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('description') }}</span>
                                             @endif
                                             </span>  
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
                                             class="col-sm-2 form-control-label">Icon <span class="valid_field">*</span></label>
                                          <div class="col-sm-10">
                                             @if($category->icon != "")
                                             <div class="row">
                                                <div class="col-sm-12 images">
                                                   <div id="user_photo" class="col-sm-4 box p-a-xs">
                                                      <a target="_blank"
                                                         href="{{ asset('uploads/primary_categories/'.$category->icon) }}"><img
                                                         src="{{ asset('uploads/primary_categories/'.$category->icon) }}"
                                                         class="img-responsive">
                                                      </a>
                                                      <br>
                                                      <div class="delete">
                                                         <a onclick="document.getElementById('user_photo').style.display='none';document.getElementById('photo_delete').value='1';document.getElementById('undo').style.display='block';"
                                                            class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                                         {{ $category->icon }}
                                                      </div>
                                                   </div>
                                                   <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                                      <a onclick="document.getElementById('user_photo').style.display='block';document.getElementById('photo_delete').value='0';document.getElementById('undo').style.display='none';">
                                                      <i class="material-icons">&#xe166;</i> {!!  __('backend.undoDelete') !!}
                                                      </a>
                                                   </div>
                                                   {!! Form::hidden('photo_delete','0', array('id'=>'photo_delete')) !!}
                                                </div>
                                             </div>
                                             @endif
                                             {!! Form::file('icon', array('class' => 'form-control','id'=>'icon','accept'=>'image/*')) !!}
                                             <small>
                                             <i class="material-icons">&#xe8fd;</i>
                                             {!!  __('backend.imagesTypes') !!}
                                             </small>
                                             <span class="help-block">
                                             @if(!empty(@$errors) && @$errors->has('icon'))
                                             <span  style="color: red;" class='validate'>{{ $errors->first('icon') }}</span>
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
                                 &#xe31b;</i> {!! __('backend.update') !!}</button>
                                 <a href="{{route('primary.categories')}}"
                                    class="btn btn-default m-t"><i class="material-icons">
                                 &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                              </div>
                           </div> 
                           {{Form::close()}}
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="SEO">
                           {{Form::open(['route'=>['primary.categories.update'],'method'=>'POST', 'files' => true])}} 
                           <input type="hidden" name="encode_id" value="{{$encode_id}}">
                           <input type="hidden" name="is_seo" value="1">
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
                                             <input class="form-control" name="seo_headline_one" value="@if(isset($seo->seo_headline_one)) {{$seo->seo_headline_one}} @endif">
                                             <span class="help-block">
                                                @if(!empty(@$errors) && @$errors->has('seo_headline_one'))
                                                   <span  style="color: red;" class='validate'>{{ $errors->first('seo_headline_one') }}</span>
                                                @endif
                                             </span> 
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
                                             <textarea class="form-control" rows="15" name="seo_description_one">@if(isset($seo->seo_description_one)){{$seo->seo_description_one}} @endif</textarea>
                                             <span class="help-block">
                                                @if(!empty(@$errors) && @$errors->has('seo_description_one'))
                                                   <span  style="color: red;" class='validate'>{{ $errors->first('seo_description_one') }}</span>
                                                @endif
                                             </span> 
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
                                             <input class="form-control" name="seo_headline_two" value="@if(isset($seo->seo_headline_two)){{$seo->seo_headline_two}} @endif"> 
                                             <span class="help-block">
                                                @if(!empty(@$errors) && @$errors->has('seo_headline_two'))
                                                   <span  style="color: red;" class='validate'>{{ $errors->first('seo_headline_two') }}</span>
                                                @endif
                                             </span> 
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
                                             <textarea class="form-control" rows="15" name="seo_description_two">@if(isset($seo->seo_description_two)){{$seo->seo_description_two}} @endif</textarea>
                                             <span class="help-block">
                                                @if(!empty(@$errors) && @$errors->has('seo_description_two'))
                                                   <span  style="color: red;" class='validate'>{{ $errors->first('seo_description_two') }}</span>
                                                @endif
                                             </span> 
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
                                             <input class="form-control" name="seo_headline_three" value="@if(isset($seo->seo_headline_three)){{$seo->seo_headline_three}} @endif">
                                             <span class="help-block">
                                                @if(!empty(@$errors) && @$errors->has('seo_headline_three'))
                                                   <span  style="color: red;" class='validate'>{{ $errors->first('seo_headline_three') }}</span>
                                                @endif
                                             </span> 
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
                                             <textarea class="form-control" name="seo_description_three" rows="15">@if(isset($seo->seo_description_three)){{$seo->seo_description_three}} @endif</textarea>
                                             <span class="help-block">
                                                @if(!empty(@$errors) && @$errors->has('seo_description_three'))
                                                   <span  style="color: red;" class='validate'>{{ $errors->first('seo_description_three') }}</span>
                                                @endif
                                             </span> 
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
                                             <textarea class="form-control" name="seo_description_other" rows="15">@if(isset($seo->seo_description_other)){{$seo->seo_description_other}} @endif</textarea>
                                          </div> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div> 
                           <div class="form-group row m-t-md">
                              <div class="col-sm-12">
                                 <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
                                 &#xe31b;</i> {!! __('backend.update') !!}</button>
                                 <a href="{{route('primary.categories')}}"
                                    class="btn btn-default m-t"><i class="material-icons">
                                 &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                              </div>
                           </div> 
                           {{Form::close()}}
                        </div>
                     </div> 
                  </div>
               </div>
            </div>
         </section> 
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

$('#url_key').keypress(function(e){
    var chr = String.fromCharCode(e.which);
    if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM-_".indexOf(chr) < 0)
    return false;
}) 

$('#position').click(function() {  
   $("html, body").animate({
        scrollTop: 200
    }, 500); 
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