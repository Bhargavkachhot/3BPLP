@extends('dashboard.layouts.master')
@section('title','Edit Profile')
@section('content')
    <div class="padding edit-package edit-user">
        <div class="box">
            <div class="box-header dker">
                <h3><i class="material-icons">&#xe3c9;</i> Edit Profile</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <span>Edit Profile</span>
                    <!-- <a href="javascript:void(0)">Edit Profile</a> -->
                </small>
            </div>
            <div class="box-tool">
                <ul class="nav">
                    <li class="nav-item inline">
                        <a class="nav-link" href="{{route("users")}}">
                            <!-- <i class="material-icons md-18">×</i> -->
                        </a>
                    </li>
                </ul>
            </div>
            <div class="box-body">
                {{Form::open(['route'=>['admin.profile.update',$user->id],'method'=>'POST', 'files' => true])}}

                <div class="form-group row">
                    <label for="name"
                           class="col-sm-2 form-control-label">Name <span class="valid_field">*</span>
                    </label>
                    <div class="col-sm-10">
                        {!! Form::text('name',$user->name, array('placeholder' => '','class' => 'form-control','id'=>'name','required'=>'')) !!}
                    </div>
                    <span class="help-block">
                                @if(!empty(@$errors) && @$errors->has('name'))
                                    <span  style="color: red;" class='validate'>{{ $errors->first('name') }}</span>
                                @endif
                            </span>
                </div>

                <div class="form-group row">
                    <label for="email"
                           class="col-sm-2 form-control-label">Email <span class="valid_field">*</span>
                    </label>
                    <div class="col-sm-10">
                        {!! Form::email('email',$user->email, array('placeholder' => '','class' => 'form-control','id'=>'email','required'=>'')) !!}
                    </div>
                    <span class="help-block">
                                @if(!empty(@$errors) && @$errors->has('email'))
                                    <span  style="color: red;" class='validate'>{{ $errors->first('email') }}</span>
                                @endif
                            </span>
                </div>

                {{--<div class="form-group row">
                    <label for="password"
                           class="col-sm-2 form-control-label">{!!  __('backend.loginPassword') !!}
                    </label>
                    <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" autocomplete="new-password">

                    </div>
                </div>--}}

                <div class="form-group row">
                    <label for="image_file"
                           class="col-sm-2 form-control-label">Profile Picture <span class="valid_field">*</span></label>
                    <div class="col-sm-10">
                        @if($user->image!="")
                            <div class="row">
                                <div class="col-sm-12 images">
                                    <div id="user_image" class="col-sm-4 box p-a-xs">
                                        <a target="_blank"
                                           href="{{ asset('uploads/users/'.$user->image) }}"><img
                                                src="{{ asset('uploads/users/'.$user->image) }}"
                                                class=" " style="height: 150px;width: 150px;">
                                        </a> 
                                        <br>
                                        <div class="delete">
                                            <a onclick="document.getElementById('user_image').style.display='none';document.getElementById('image_delete').value='1';document.getElementById('undo').style.display='block';"
                                            class="btn btn-sm btn-default">{!!  __('backend.delete') !!}</a>
                                            {{ $user->image }}
                                        </div>
                                    </div> 
                                    <div id="undo" class="col-sm-4 p-a-xs" style="display: none">
                                        <a onclick="document.getElementById('user_image').style.display='block';document.getElementById('image_delete').value='0';document.getElementById('undo').style.display='none';">
                                            <i class="material-icons">&#xe166;</i> {!!  __('backend.undoDelete') !!}
                                        </a>
                                    </div>

                                    {!! Form::hidden('image_delete','0', array('id'=>'image_delete')) !!}
                                </div>
                            </div>
                        @endif

                        {!! Form::file('image', array('class' => 'form-control','id'=>'image','accept'=>'image/*')) !!}
                        <small>
                            <i class="material-icons">&#xe8fd;</i>
                            {!!  __('backend.imagesTypes') !!}
                        </small>
                        <span class="help-block">
                                @if(!empty(@$errors) && @$errors->has('image'))
                                    <span  style="color: red;" class='validate'>{{ $errors->first('image') }}</span>
                                @endif
                            </span>
                    </div>
                </div>

                


                <div class="form-group row m-t-md">
                    <div class="offset-sm-2 col-sm-10">
                        <button type="submit" class="btn btn-primary m-t"><i class="material-icons">
                                &#xe31b;</i> {!! __('backend.update') !!}</button>
                        <a href="{{route('adminHome')}}"
                           class="btn btn-default m-t"><i class="material-icons">
                                &#xe5cd;</i> {!! __('backend.cancel') !!}</a>
                    </div>
                </div>

                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection