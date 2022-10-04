<?php

namespace App\Exports;

use App\Models\User; 
use App\Models\UserRole;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use App\Models\Role;
use Session;


class UsersExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Name',  
            'Email',
            'Mobile Number',
            'Roles',
            'Is Active',
            'Email Verified', 
            'Created at',
            'Updated at' 
        ];
    } 
    public function query()
    {   
        $sort='users.created_at';  
        $sortBy='DESC'; 
        $users = User::with('roles')->where('status','!=',2)->orderBy($sort,$sortBy); 
        $array = $users->pluck('id')->toArray(); 
        Session::put('data', $array); 
        return $users;
    }
    public function map($users): array
    {   
        $data = Session::get('data'); 
        if($users->status == 1){
            $status = "Yes";
        }else{
            $status = "No";
        }

        if($users->email_verified != 1){
            $email_verified = "No";
        }else{
            $email_verified = "Yes";
        }
 
        if(count($users->roles) > 0 ){
            foreach ($users->roles as $key => $value) {
               $role_names[] = Role::where('id',$value->role_id)->pluck('name')->first();
            }
        }else{
            $role_names[] = '';
        }
        return [ 
            array_search ($users->id, $data)+1,
            $users->name, 
            $users->email,
            $users->mobile_number, 
            implode(', ', $role_names),
            $status,
            $email_verified,   
            $users->created_at,
            $users->updated_at,
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
