<?php

namespace App\Exports;

use App\Models\Role; 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use Maatwebsite\Excel\Concerns\WithEvents; 
use Session;


class RolesExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Name',  
            'Slug',
            'Module Name',
            'Read',
            'Create',
            'Update',
            'Delete',
            'status',  
            'Created at',
            'Updated at' 
        ];
    } 
    public function query()
    {   
        
        $roles = Role::rightJoin('role_module_permission','role_module_permission.role_id','=','roles.id')
        ->rightJoin('role_modules','role_module_permission.role_module_id','=','role_modules.id')
        ->select('roles.*','role_module_permission.*','role_modules.name as module_name');

        $array = $roles->orderby('roles.id','ASC')->pluck('id')->toArray(); 
        Session::put('data', $array); 

        return $roles;
    }
    public function map($roles): array
    {   
        $data = Session::get('data'); 
        if($roles->read != 1){
            $read = "No";
        }else{
            $read = "Yes";
        }
        if($roles->create != 1){
            $create = "No";
        }else{
            $create = "Yes";
        }
        if($roles->update != 1){
            $update = "No";
        }else{
            $update = "Yes";
        }
        if($roles->delete != 1){
            $delete = "No";
        }else{
            $delete = "Yes";
        }
        if($roles->status != 1){
            $status = "In-Active";
        }else{
            $status = "Active";
        }
        return [
            array_search ($roles->id, $data)+1,
            $roles->name, 
            $roles->slug,
            $roles->module_name,
            $read,
            $create,  
            $update,
            $delete, 
            $status,
            $roles->created_at,
            $roles->updated_at,
        ];
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    } 
}
