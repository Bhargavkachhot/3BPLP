@extends('dashboard.layouts.master') 
@section('title', __('Edit CMS'))  
@section('content')
<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),5); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->update == 1)
<link href="{{ asset('assets/dashboard/js/iconpicker/fontawesome-iconpicker.min.css') }}" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <div class="padding edit-package">
        <div class="box">
            <div class="box-header dker"> 
                <h3><i class="material-icons">&#xe3c9;</i> Edit CMS
                </h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <a href="{{ route('cms') }}">CMS</a>
                </small>
            </div> 
            <div class="box-body">
                {{ Form::open(['route' => ['cms.update', 'id' => $cms->id], 'method' => 'POST', 'files' => true,'enctype' => 'multipart/form-data']) }}
                <div class="personal_informations">
                    <!-- <h3>{!! __('backend.cms') !!}</h3>
                        <br>
                        <br> -->
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{!! __('backend.newpage') !!} <span class="valid_field">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="page_name" id="page_name" class="form-control" onfocus="validateMsgHide('page_name')" placeholder="Name"
                                value="{{ $cms->page_title }}">
                        </div>
                         @if ($errors->has('page_name'))
                                <span class="help-block">
                                    <span style="color: red;" id="error_page_name" class='validate'>{{ $errors->first('page_name') }}</span>
                                </span>
                            @endif
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label">{!! __('backend.pagecontent') !!} <span class="valid_field">*</span></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="page_content" name="page_content" onfocus="validateMsgHide('page_content')"
                                autofocus>{{ isset($cms->page_content) ? urldecode($cms->page_content) : old('page_content') }}</textarea>
                        </div>
                        @if ($errors->has('page_content'))
                                <span class="help-block">
                                    <span style="color: red;" id="error_page_content" class='validate'>{{ $errors->first('page_content') }}</span>
                                </span>
                            @endif
                    </div>

                    
                </div>

                <div class="form-group row m-t-md">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary m-t"><i class="material-icons">&#xe31b;</i>
                            {!! __('backend.update') !!}</button>
                        <a href="{{ route('cms') }}" class="btn btn-default m-t">
                            <i class="material-icons">&#xe5cd;</i> {!! __('backend.cancel') !!}
                        </a>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
<script src="{{ asset('assets/dashboard/js/iconpicker/fontawesome-iconpicker.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/summernote/dist/summernote.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


    <script>
        $(function() {
            $('.icp-auto').iconpicker({
                placement: 'topLeft'
            });
        });

        
        // update progress bar
        function progressHandlingFunction(e) {
            if (e.lengthComputable) {
                $('progress').attr({
                    value: e.loaded,
                    max: e.total
                });
                // reset progress on complete
                if (e.loaded == e.total) {
                    $('progress').attr('value', '0.0');
                }
            }
        }
    </script>
    <script>
        {{-- CKEDITOR.on('instanceReady', function(ev) {
            document.getElementById('eMessage').innerHTML = 'Instance <code>' + ev.editor.name + '<\/code> loaded.';

            document.getElementById('eButtons').style.display = 'block';
        }); --}}

        {{-- function InsertHTML() {
            var editor = CKEDITOR.instances.editor1;
            var value = document.getElementById('htmlArea').value;

            if (editor.mode == 'wysiwyg') {
                editor.insertHtml(value);
            } else
                alert('You must be in WYSIWYG mode!');
        }

        function InsertText() {
            var editor = CKEDITOR.instances.editor1;
            var value = document.getElementById('txtArea').value;

            if (editor.mode == 'wysiwyg') {
                editor.insertText(value);
            } else
                alert('You must be in WYSIWYG mode!');
        }

        function SetContents() {
            var editor = CKEDITOR.instances.editor1;
            var value = document.getElementById('htmlArea').value;

            editor.setData(value);
        }

        function GetContents() {
            var editor = CKEDITOR.instances.editor1;
            alert(editor.getData());
        }

        function ExecuteCommand(commandName) {
            var editor = CKEDITOR.instances.editor1;

            if (editor.mode == 'wysiwyg') {
                editor.execCommand(commandName);
            } else
                alert('You must be in WYSIWYG mode!');
        }

        function CheckDirty() {
            var editor = CKEDITOR.instances.editor1;
            alert(editor.checkDirty());
        }

        function ResetDirty() {
            var editor = CKEDITOR.instances.editor1;
            editor.resetDirty();
            alert('The "IsDirty" status has been reset');
        }

        function Focus() {
            CKEDITOR.instances.editor1.focus();
        }

        function onFocus() {
            document.getElementById('eMessage').innerHTML = '<b>' + this.name + ' is focused </b>';
        }

        function onBlur() {
            document.getElementById('eMessage').innerHTML = this.name + ' lost focus';
        } --}}
        
        {{-- CKEDITOR.replace('page_content', {
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
        }); --}}


        CKEDITOR.replace('page_content');

    </script>
@else

<input type="hidden" value="{{route('adminHome')}}" id="redirect_url">
<script type="text/javascript">
   var redirect_url = $('#redirect_url').val();
   window.location.replace(redirect_url); 
</script>

@endif 
    
@endsection 
