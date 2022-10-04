@extends('dashboard.layouts.master')
@section('title', __('CMS'))
@section('content')  

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),5); 
?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1)


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" type="text/css" />
<div class="padding">
   <div class="box">
      <div class="box-header dker">
         <h3>CMS</h3>
         <small>
         <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /  
         <span>CMS</span>
         </small>
      </div>
      {{Form::open(['method'=>'POST','id'=>'bulk_action_form'])}}  

      @if(isset($allowed_permissions) && $allowed_permissions->create == 1)
         <a href="{{route('cms.create')}}"   class="btn primary m-a pull-right"><i class="fa fa-plus"aria-hidden="true"></i>
         Create</a>
      @endif

      <div class="p-a-md">
         <h5>CMS</h5>
      </div>
      <div class="table-responsive">  
         <table id="categories" class="display" cellspacing="0" width="100%">
            <thead>
               <tr> 
                  <th>Page Title</th> 
                  <!-- <th data-orderable="false">Status</th> -->
                  <th data-orderable="false" style="width:100px;">Options</th>
               </tr>
            </thead>
         </table>
         <input type="hidden" name="selected_ids" id="selected_ids">

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
<link type="text/css"  rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js"></script>
<link type="text/css"  rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.10/css/dataTables.checkboxes.css">
<script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.10/js/dataTables.checkboxes.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
   $(document).ready(function($) {
   
   
   $.noConflict();
    var table = $('#categories').DataTable({
   
    'ajax': '{{route('cms.anyData')}}', 
    'order': []
   });   
   
   
     
   
   
   $(document).on("click", ".remove-record", function(e) { 
   e.preventDefault();
   var link = $(this).attr("href"); 
   Swal.fire({
   title: 'Are you sure ?',
   text: "You want to delete this page?",
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'Yes'
   }).then((result) => {
   if (result.isConfirmed) {
   var id = $(this).attr("id"); 
   $.ajax({
            url: "{{route('cms.delete')}}",
            type: 'POST',
            data: {id: id},
            error: function() {
              alert('Something is wrong, couldn\'t delete page');
            },
            success: function(data) { 
                if(data == 1){ 
                table.ajax.reload();
   
                     Swal.fire({
                        title: 'Success',
                        text: "Your page Deleted Successfully!",
                        icon: 'success', 
                      })
                }else{    
                     Swal.fire({
                        title: 'Error',
                        text: "Sorry can't delete this page.",
                        icon: 'error', 
                      })
                }
               
            }
   
        });
   }
   })
    }); 
   
   });
</script>
@endpush