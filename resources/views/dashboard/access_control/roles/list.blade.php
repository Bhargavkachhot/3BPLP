@extends('dashboard.layouts.master')
@section('title', __('Roles'))
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
         <h3>Roles </h3>
         <small>
         <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
         <span>Access Control</span> /
         <span>Roles</span>
         </small>
      </div>
      <!-- @if($roles == 0)
         <a href="{{route('roles.create')}}"   class="btn primary m-a pull-right">+ Create Role</a>
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
         @endif -->
      {{Form::open(['method'=>'post'])}}
      <a href="{{route('export.roles')}}"   class="btn primary m-a pull-right"><i class="fa fa-download" aria-hidden="true"></i>
      Export</a>

      @if(isset($allowed_permissions) && $allowed_permissions->create == 1)
      <a href="{{route('roles.create')}}"   class="btn primary m-a pull-right"><i class="fa fa-plus" aria-hidden="true"></i>
      Create Role</a>
      @endif

      <div class="p-a-md">
         <h5>Roles</h5>
      </div>
      <div class="table-responsive">
         <table class="table table-bordered m-a-0" id="roles">
            <thead class="dker">
               <tr>
                  <th>id</th>
                  <th>Role name</th>
                  <th>Role slug</th>
                  <th>options</th>
               </tr>
            </thead>
            <tbody id="roles">
            </tbody>
         </table>
      </div>
      {{Form::close()}} 
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
   
         var action_url = "{!!  route('roles.anyData') !!} ";
       
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
                
              },
              {
                 data: 'slug',
                 name: 'slug',
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
   
    
    
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
   $(document).on("click", ".remove-record", function(e) { 
   e.preventDefault();
   var link = $(this).attr("href"); 
   Swal.fire({
   title: 'Are you sure ?',
   text: "You want to delete this record?",
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'Yes'
   }).then((result) => {
   if (result.isConfirmed) {
   var id = $(this).attr("id"); 
   $.ajax({
              url: "{{route('roles.delete')}}",
              type: 'POST',
              data: {id: id},
              error: function() {
                alert('Something is wrong, couldn\'t delete record');
              },
              success: function(data) { 
                 $('#roles').DataTable().ajax.reload();   
                 if(data == 1){
                     Swal.fire({
                       title: 'Success',
                       text: "Your Record Deleted Successfully!",
                       icon: 'success', 
                     })
                 }else{
                     Swal.fire({
                       title: 'Error',
                       text: "Can't delete this role. Role is assigned to user.",
                       icon: 'error', 
                     })
                 }
                 
              }
   
          });
   }
   })
      });
</script>
@endpush