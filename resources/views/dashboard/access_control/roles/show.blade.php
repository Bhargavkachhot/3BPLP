@extends('dashboard.layouts.master')
@section('title', __('Show Rroles'))
@section('content') 

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),2); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1)
<style type="text/css"> 
div#roles_length {
    display: none;
} 
div#roles_filter {
    display: none;
}
div#roles_paginate {
    display: none;
}
div#roles_info {
    display: none;
}
</style> 
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" type="text/css" />
<div class="padding">
   <div class="box">
      <div class="box-header dker">
         <h3><i class="btn btn-sm show-eyes list show-icon"></i> Show Role</h3>
         <small>
         <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
         <span>Access Control</span> /
         <a href="{{ route('roles') }}">Roles</a> /
         <span>Edit Role</span>
         </small>
      </div> 
      <form> 
         <div class="p-a-md">
        <h5>Edit Role</h5>
    </div>
         <div class="col-sm-12">
            <div class="form-group">
               <label>Role Name</label>
               <input  class="form-control has-value" name="role_name" type="text" value=" {{$role_name}}" disabled>
            </div>
         </div>
         <div class="col-sm-12" >
            <div class="form-group">
               <label>Role Slug</label>
               <input  class="form-control has-value" name="role_slug" type="text" value=" {{$role_slug}}" disabled>
            </div>
         </div>
         <br>
         <div class="table-responsive">
            <table class="table table-bordered m-a-0" id="roles">
               <thead class="dker">
                  <tr>
                     <th>id</th>
                     <th>Module Name</th>
                     <th>Read</th>
                     <th>Create</th>
                     <th>Update</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody id="roles">
               </tbody>
            </table>
         </div>
      </form>
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
   
         var action_url = "{!!  route('roles.edit.permission.filter') !!} ";
       
          $('#roles').DataTable({
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
                   encode_id:'{{$encode_id}}',
                   page:'show',
                  }
              },
              columns: [
              {
                  data: 'id',
                  name: 'id',
                  visible:false
                
              },
              {
                  data: 'name',
                  name: 'name',
                  orderable: false,
                
              }, 
              {
                  data: 'read',
                  name: 'Read',
                  orderable: false,
                  searchable: false
              },
              {
                  data: 'create',
                  name: 'Create',
                  orderable: false,
                  searchable: false
              },
              {
                  data: 'update',
                  name: 'Update',
                  orderable: false,
                  searchable: false
              },
              {
                  data: 'delete',
                  name: 'Delete',
                  orderable: false,
                  searchable: false
              },
              ],
              order: ['0', 'DESC']
          });
      }
   
   });
    
   
   
  
</script>
@endpush