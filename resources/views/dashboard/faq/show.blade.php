@extends('dashboard.layouts.master')
@section('title','Show FAQ')
@section('content')
<?php
   $allowed_permissions = @Helper::GetRolePermission(Auth::id(),6); 
   ?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1)
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
         <h3><i class="fa fa-plus"></i> Show FAQ</h3>
         <small>
         <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> / 
         <a href="{{ route('faq') }}">FAQ</a> /
         <span>Show FAQ</span>  
         </small>
      </div>
      <div class="box-tool">
         <ul class="nav">
            <li class="nav-item inline">
               <a class="nav-link" href="{{route("faq")}}">
               <!-- <i class="material-icons md-18">×</i> -->
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
                        <div class="form-group row">
                           <div class="col-sm-12">
                              <div class="row">   
                                 <label for="position"
                                    class="form-control-label">Position 
                                 </label> 
                                 <input type="number" name="position" value="{{$faq->position}}" autofocus class="form-control" disabled> 
                                 <label for="category_name"
                                    class="form-control-label">Question  
                                 </label> 
                                 <input type="text" name="question" value="{{$faq->question}}" class="form-control" disabled>  
                                 <label for="category_name"
                                    class="form-control-label">Answer  
                                 </label> 
                                 <textarea class="form-control" id="page_content" name="answer"  disabled>{{$faq->answer}}</textarea> 
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-group row m-t-md">
                        <div class="col-sm-12"> 
                           <a href="{{route('faq')}}"
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