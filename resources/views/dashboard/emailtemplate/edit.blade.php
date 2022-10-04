@extends('dashboard.layouts.master') 
@section('title', __('backend.emailtemplate'))

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),3); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1)

@push("after-styles")
    <link href="{{ asset("assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css") }}" rel="stylesheet">

    <link rel= "stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
@endpush
@section('content')
    <div class="padding edit-package">
        <div class="box">
            <div class="box-header dker"> 
                <h3><i class="material-icons">&#xe3c9;</i>  {{ __('backend.emailtemplate') }}
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <span>Website Settings</span> /
                    <a href="{{ route('emailtemplate') }}">{{ __('backend.emailtemplate') }}</a> /
                    <span>Edit Email Template</span> 
                </small>
            </div> 
            <div class="box-body">
                {{Form::open(['route'=>['emailtemplate.update',"id"=>$emailtemplate->id],'method'=>'POST', 'files' => true])}}
                <div class="personal_informations">
                    <h3>{!!  __('backend.emailtemplate') !!}</h3>
                    <br>
                    <br>

                    <div class="form-group row">
                        <emailtemplate class="col-sm-2 form-control-emailtemplate">Title <span class="valid_field">*</span></emailtemplate>
                        <div class="col-sm-10">
                            <input type="text" name="title" id="title" class="form-control" placeholder="Title " value="{{$emailtemplate->title}}">
                        </div>
                    @if ($errors->has('title'))
                                <span class="help-block">
                                    <span style="color: red;" class='validate'>{{ $errors->first('title') }}</span>
                                </span>
                            @endif
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">Subject <span class="valid_field">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Name" value="{{$emailtemplate->subject}}">
                        </div>
                    @if ($errors->has('subject'))
                                <span class="help-block">
                                    <span style="color: red;" class='validate'>{{ $errors->first('subject') }}</span>
                                </span>
                            @endif
                    </div>


                  


                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">Content <span class="valid_field">*</span></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="page_content" name="content" autofocus >{{ isset($emailtemplate->content)?urldecode($emailtemplate->content):old('content') }}</textarea>
                        </div>
                    </div>
                    @if ($errors->has('page_content'))
                                <span class="help-block">
                                    <span style="color: red;" class='validate'>{{ $errors->first('page_content') }}</span>
                                </span>
                            @endif

                   

                </div>

                <div class="form-group row m-t-md">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary m-t"><i class="material-icons">&#xe31b;</i> {!! __('backend.update') !!}</button>
                        <a href="{{ route('emailtemplate')}}" class="btn btn-default m-t">
                            <i class="material-icons">&#xe5cd;</i> {!! __('backend.cancel') !!}
                        </a>
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
@push("after-scripts")
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
@endpush
