@extends('dashboard.layouts.master')
@section('title','Dashboard')
@push("after-styles")
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/flags.css') }}" type="text/css"/>
@endpush
@section('content')
<?php 
use App\Models\User;
?>
    <div class="padding p-b-0 upskild-dashboard">
        <div class="margin">
            <div class="row">
                 <div class="col-xs-6">
                <h5 class="m-b-0 _300">{{ __('backend.hi') }} <span
                        class="text-primary">{{ Auth::user()->name }}</span>, {{ __('backend.welcomeBack') }}
                </h5>
                </div>
                 <div class="col-xs-6"> 
                <!-- <form action="{{ route('dashboardfilter')}}" method="post" style="padding-left: %;">
                    @csrf
                        
                    
                        <input type="text" class="form-control" style="color: #001645;font-weight:500;width: 220px;margin-right: 8px;" value="{{ isset($filterdate)?$filterdate:old('date_filter') }}" name="date_filter" id="date_filter"/>
                        <input type="submit" name="filter_submit" class="btn btn-primary" value="Filter" />
                        <a href="{{ route('adminHome')}}"><input type="button" name="clear" class="btn btn-danger" value="Clear"  /></a>
                </form> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                
                    <div class="col-xs-4">
                        <div class="box p-a" style="cursor: pointer">
                            <a href="">
                                <div class="pull-left m-r">
                                    <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                </div>
                                <div class="clear">
                                    <div class="text-muted">Total leads</div>
                                        <h4 class="m-a-0 text-md _600">1000</h4>
                                </div>
                            </a>
                        </div>
                    </div>  
                    <div class="col-xs-12">
                       
                    </div>
                </div>
            </div>  
        </div>
    </div>
@endsection
@push("after-scripts")
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      
@endpush