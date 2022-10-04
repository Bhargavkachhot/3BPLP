@extends('dashboard.layouts.master')
@section('title', __('backend.emailtemplate'))
@section('content')
<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),3); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1)

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" type="text/css" />  
    <div class="padding">
        <div class="box">

            <div class="box-header dker">
                <h3>{{ __('backend.emailtemplate') }}</h3>
                <small>
                    <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
                    <span>Website Settings</span> /
                    <span>{{ __('backend.emailtemplate') }}</span>
                </small>
            </div>

            <div class="box-tool">
                <ul class="nav">
                        @if(@Auth::user()->permissionsGroup->webmaster_status)
                            <li class="nav-item inline">
                                <a class="btn btn-fw primary" href="{{route('emailtemplate.create')}}">
                                    <i class="material-icons">&#xe02e;</i>
                                    &nbsp; New Email Templete
                                </a>
                            </li>
                        @endif
                </ul>
            </div>

                {{--<div class="dker b-b displayNone" id="filter_div">
                    <div class="p-a">
                        {{Form::open(['method'=>'GET','id'=>'filter_form','target'=>''])}}
                        <div class="filter_div">
                            <div class="row">
                                <div class="col-md-4"></div>
                                    <div class="col-md-3 col-xs-6 m-b-5p">
                                       <input placeholder="Search For" class="form-control" id="find_q" autocomplete="off" name="find_q" type="text">
                                    </div>
                                <div class="col-md-1 col-xs-6 m-b-5p">
                                    <button class="btn white w-full" id="search-btn" type="submit"><i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}

            @if($emailtemplate == 0)
                <div class="row p-a">
                    <div class="col-sm-12">
                        <div class=" p-a text-center ">
                            {{ __('backend.noData') }}
                            <br>
                            @if(@Auth::user()->permissionsGroup->webmaster_status)
                                <br>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($emailtemplate > 0)
                {{Form::open(['method'=>'post'])}}
                <div class="table-responsive">
                    <table class="table table-bordered m-a-0" id="emailtemplate">
                        <thead class="dker">
                        <tr>
                            <th>id</th>
                            <th>{{ __('backend.topicName') }}</th>
                            <th>{{ __('backend.subject') }}</th>
                            <th>{{ __('backend.options') }}</th>
                        </tr>
                        </thead>
                        <tbody id="emailTemplateTable">

                        </tbody>
                    </table>

                </div>
               
                {{Form::close()}}
            @endif
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
 <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
           $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
           load_data();
           function load_data() 
           {
        
              var action_url = "{!!  route('emailtemplate.anyData') !!} ";
            
               $('#emailtemplate').DataTable({
                   processing: true,
                   serverSide: true,
                   responsive: true,
                   ordering: true,
                   columnDefs: [{
                       'bSortable': false,
                       'aTargets': [0,3]
                   }],
                   ajax: {
                       url : action_url,
                       type: 'POST',
                       data:{
                       
                       }
                   },
                   columns: [
                   {
                       data: 'id',
                       name: 'id',
                       visible:false
                     
                   },
                   {
                       data: 'title',
                       name: 'title',
                     
                   },
                   {
                      data: 'subject',
                      name: 'subject',
                   },
                  
                   {
                       data: 'options',
                       orderable: false,
                       searchable: false
                   }
                   ],
                   order: ['0', 'DESC']
               });
           }
        
        });
    
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#action").change(function () {
            if (this.value == "delete") {
                $("#submit_all").css("display", "none");
                $("#submit_show_msg").css("display", "inline-block");
            } else {
                $("#submit_all").css("display", "inline-block");
                $("#submit_show_msg").css("display", "none");
            }
        });


        $("#filter_btn").click(function () {
            $("#filter_div").slideToggle();
        });

        $("#find_q").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#doctorTypeTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
@endpush
