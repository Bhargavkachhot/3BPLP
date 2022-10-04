@extends('dashboard.layouts.master')
@section('title', 'Email Template Language')
@push("after-styles")
    <link href="{{ asset('assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css') }}" rel="stylesheet">

    <link rel= "stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
 
@endpush
@section('content')
    <div class="padding edit-package">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">
                        &#xe02e;</i> Email Template Language
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="{{ route('emailtemplate') }}">{{ __('backend.emailtemplate') }}</a>
                </small>
            </div>
            <div class="box-body">
                {{Form::open(['route'=>['emailtemplate.storeLang'],'method'=>'POST', 'files' => true,'enctype' => 'multipart/form-data', 'id' => 'emailtemplateForm' ])}}

                <div class="personal_informations">
                    <h3>{!!  __('backend.emailtemplate') !!}</h3>
                    <br>
                    <br>

                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{!!  __('backend.language') !!}</label>
                        <div class="col-sm-10">
                            <input type="text" name="template_lang" id="template_lang" class="form-control" placeholder="Email Template Language" value="{{$languageData->title}}" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-emailtemplate">Title</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ old('title', isset($EmailTemplate->title) ? $EmailTemplate->title : '') }}" dir="{{$languageData->direction}}">
                        </div>
                    </div>

                 
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-emailtemplate">Subject </label>
                        <div class="col-sm-10">
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject " value="{{ old('subject', isset($EmailTemplate->subject) ? $EmailTemplate->subject : '') }}" dir="{{$languageData->direction}}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">Content</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="page_content" name="content" autofocus dir="{{$languageData->direction}}">{{old('content',isset($EmailTemplate->content) ? $EmailTemplate->content : '')}}</textarea>
                        </div>
                    </div>

                    <input type="hidden" name="template_language_id" value="{{$languageData->id}}" />
                    <input type="hidden" name="template_parent_id" value="{{$parentData->id}}" />
                    <input type="hidden" name="template_id" value="{{isset($EmailTemplate->id)?$EmailTemplate->id:''}}" />
                </div>

                    <div class="form-group row m-t-md">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary m-t" id="submitDetail"><i class="material-icons">&#xe31b;</i> {!! (isset($EmailTemplate->id)?__('backend.update'):__('backend.add')); !!}</button>
                            <a href="{{ route('emailtemplate')}}" class="btn btn-default m-t">
                                <i class="material-icons">
                                &#xe5cd;</i> {!! __('backend.cancel') !!}
                            </a>
                    </div>
                </div>


                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection
@push("after-scripts")
    <script src="{{ asset('assets/dashboard/js/iconpicker/fontawesome-iconpicker.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/summernote/dist/summernote.js') }}"></script>
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
            CKEDITOR.on( 'instanceReady', function( ev ) {
            document.getElementById( 'eMessage' ).innerHTML = 'Instance <code>' + ev.editor.name + '<\/code> loaded.';
                                                                                                                                                                
            document.getElementById( 'eButtons' ).style.display = 'block';
        });

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
                    focus: onFocus,
                    blur: onBlur,
                    pluginsLoaded: function(evt) {
                        var doc = CKEDITOR.document,
                            ed = evt.editor;
                        if (!ed.getCommand('bold')) doc.getById('exec-bold').hide();
                        if (!ed.getCommand('link')) doc.getById('exec-link').hide();
                    }
                }
            });

             CKEDITOR.replace('page_content2', {
                on: {
                    focus: onFocus,
                    blur: onBlur,
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
