@extends('dashboard.layouts.master')
@section('title', __('FAQ'))
@section('content')  
<?php
   $allowed_permissions = @Helper::GetRolePermission(Auth::id(),1); 
   ?>
@if(isset($allowed_permissions) && $allowed_permissions->read == 1) 
<style type="text/css">
   p { 
   margin-bottom: 0;
   }
</style>
<div class="padding">
   <div class="box">
      <div class="box-header dker">
         <h3>FAQ</h3>
         <small>
         <a href="{{ route('adminHome') }}">{{ __('backend.home') }}</a> / 
         <span>FAQ</span>
         </small>
      </div>
      {{Form::open(['route'=>['faq.bulk.action'],'method'=>'POST' ,'id'=>'bulk_action_form'])}}
      @if(isset($allowed_permissions) && $allowed_permissions->delete == 1)
      <div class="bulk-action">
         <select name="action" id="action" class="form-control c-select w-sm inline v-middle" required>
            <option disabled selected>Bulk action</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="delete">Delete</option>
         </select>
         <button type="button" class="btn white" id="apply_bulk_action_button" >Apply</button>
      </div>
      @endif 
      @if(isset($allowed_permissions) && $allowed_permissions->create == 1)
      <a href="{{route('faq.create')}}" class="btn primary m-a pull-right"><i class="fa fa-plus" aria-hidden="true"></i>
      Create</a>
      @endif  
      <div class="p-a-md">
         <h5>FAQ</h5>
      </div>
      <div class="table-responsive">
         <table id="categories" class="display" cellspacing="0" width="100%">
            <thead>
               <tr >
                  <th></th>
                  <th>Questions</th>
                  <th>Answers</th>
                  <th>Position</th>
                  <th data-orderable="false">Status</th>
                  <th data-orderable="false">options</th>
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
   
    'ajax': '{{route('faq.anyData')}}',
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
       text: "You want to "+action+" all selected faq?",
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
   text: "You want to delete this faq?",
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'Yes'
   }).then((result) => {
   if (result.isConfirmed) {
   var id = $(this).attr("id"); 
   $.ajax({
            url: "{{route('faq.delete')}}",
            type: 'POST',
            data: {id: id},
            error: function() {
              alert('Something is wrong, couldn\'t delete faq');
            },
            success: function(data) { 
                if(data == 1){
   
                table.ajax.reload();
   
                     Swal.fire({
                        title: 'Success',
                        text: "Your faq Deleted Successfully!",
                        icon: 'success', 
                      })
                }else{    
                     Swal.fire({
                        title: 'Error',
                        text: "Sorry can't delete this faq cause it's assigned to subcategories, product categories and example products.",
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