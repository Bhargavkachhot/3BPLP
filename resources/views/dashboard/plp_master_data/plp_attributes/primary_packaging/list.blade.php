@extends('dashboard.layouts.master')
@section('title', __('Primary Packaging'))
@section('content')  

<?php
  $allowed_permissions = @Helper::GetRolePermission(Auth::id(),4); 
?> 
@if(isset($allowed_permissions) && $allowed_permissions->read == 1) 
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" type="text/css" />
<div class="padding">
   <div class="box">
      <div class="box-header dker">
         <h3>PLP Master DATA</h3> 
         <small>
         <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> /
         <span>PLP Master Data</span> /
      <span>PLP Attributes</span> / 
         <span>Primary Packaging</span>
         </small>
      </div> 
      {{Form::open(['route'=>['primary.packaging.bulk.action'],'method'=>'POST' ,'id'=>'bulk_action_form'])}}
      <input type="hidden" name="bulk_ids" id="bulk_ids">
      @if(isset($allowed_permissions) && $allowed_permissions->delete == 1)
         <div class="bulk-action">
            <select name="action" id="action" class="form-control c-select w-sm inline v-middle" required>
               <option disabled selected>Bulk action</option>
               <option value="active">Active</option>
               <option value="inactive">Inactive</option>
               <option value="delete">Delete</option>
            </select>
            <button type="button" class="btn white" id="apply_bulk_action_button">Apply</button>
         </div>
      @endif

      <a href="{{route('export.primary.packaging')}}"   class="btn primary m-a pull-right"><i class="fa fa-download" aria-hidden="true"></i>
      Export</a>

      @if(isset($allowed_permissions) && $allowed_permissions->create == 1)
         <a href="{{route('primary.packaging.import')}}"   class="btn primary m-a pull-right"><i class="fa fa-upload" aria-hidden="true"></i>
         Import</a>
      @endif

      @if(isset($allowed_permissions) && $allowed_permissions->create == 1)
         <a href="{{route('primary.packaging.create')}}"   class="btn primary m-a pull-right"><i class="fa fa-plus" aria-hidden="true"></i>
         Create</a>
      @endif

      <div class="p-a-md">
         <h5>Primary Packaging</h5>
      </div>
      <div class="table-responsive"> 
      <table id="attributes" class="display" cellspacing="0" width="100%">
            <thead>
               <tr>
                  <th></th>  
                  <th>Primary Packaging</th> 
                  <th>Subcategories</th>
                  <th>Product Categories</th> 
                  <th>Position</th>  
                  <th data-orderable="false">  Status</th> 
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
    var table = $('#attributes').DataTable({
   
    'ajax': '{{route('primary.packaging.anyData')}}',
    'columnDefs': [
       {
          'targets': 0,
          'checkboxes': {
             'selectRow': true, 
          }
       },
    ],
    'select': {
       'style': 'multi',
       'selector': 'td:first-child'
    }, 
    'order': []
   });   
   
   
    $(document).on("click", "#apply_bulk_action_button", function(e) {  
    var form = this; 
    var rows_selected = table.column(0).checkboxes.selected(); 
     
    $('#selected_ids').val(rows_selected.join(","));  
    var selected_ids = $('#selected_ids').val();
   var action = $('#action').val();  
    if(action == null){
       Swal.fire({
       title: 'No bulk action selected',
       text: "You need to select any bulk action to perform the task.",
       icon: 'warning',
       showCancelButton: false,
       showConfirmButton: true, 
       confirmButtonColor: '#3085d6', 
       confirmButtonText: 'OK' 
       }).then((result) => { 
          return false;
       });
    }else if(selected_ids == ''){ 
      Swal.fire({
       title: 'No Records selected',
       text: "You need to select any record to perform the task.",
       icon: 'warning',
       showCancelButton: false,
       showConfirmButton: true, 
       confirmButtonColor: '#3085d6', 
       confirmButtonText: 'OK' 
       }).then((result) => { 
          return false;
       });
    }else{
       Swal.fire({
       title: 'Are you sure ?',
       text: "You want to "+action+" all selected primary packaging?",
       icon: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Yes'
       }).then((result) => {
       if (result.isConfirmed) { 
   
          $('#bulk_action_form').submit();
          return false;
       }
       });
    }
   
   });
   
   
   
   $(document).on("click", ".remove-record", function(e) { 
   e.preventDefault();
   var link = $(this).attr("href"); 
   Swal.fire({
   title: 'Are you sure ?',
   text: "You want to delete this primary packaging?",
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'Yes'
   }).then((result) => {
   if (result.isConfirmed) {
   var id = $(this).attr("id"); 
   $.ajax({
            url: "{{route('primary.packaging.delete')}}",
            type: 'POST',
            data: {id: id},
            error: function() {
              alert('Something is wrong, couldn\'t delete primary packaging');
            },
            success: function(data) { 
                if(data == 1){
   
                table.ajax.reload();
   
                     Swal.fire({
                        title: 'Success',
                        text: "Your primary packaging Deleted Successfully!",
                        icon: 'success', 
                      })
                }else{    
                     Swal.fire({
                        title: 'Error',
                        text: "Sorry can't delete this primary packaging cause it's assigned to child element.",
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