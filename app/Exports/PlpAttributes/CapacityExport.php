<?php

namespace App\Exports\PlpAttributes;

use App\Models\Seo;  
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Helper;
use Session; 
use App\Models\Capacity; 
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class CapacityExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id', 
            'Capacity',  
            'Position',
            'Status',
            'Subcategories',
            'Product categories', 
            'Created at',
            'Updated at' 
        ];
    } 
    public function query()
    {   
        $sort='capacity.created_at';  
        $sortBy='DESC';     
        $capacity = Capacity::with('capacity_subcategories.subcategory')->where('status','!=',2)->orderBy($sort,$sortBy); 
        $array = $capacity->pluck('id')->toArray(); 
        Session::put('data', $array);  
        return $capacity;
    }
    public function map($capacity): array
    {    
        $capacity_subcategories = Helper::GetCapacitySubcategories($capacity->id); 
        $capacity_categories = Helper::GetCapacityProductCategories($capacity->id);   
        $data = Session::get('data'); 
        if($capacity->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($capacity->id, $data)+1,  
            $capacity->capacity, 
            $capacity->position, 
            $status,   
            implode(', ', $capacity_subcategories),
            implode(', ', $capacity_categories),
            $capacity->created_at,
            $capacity->updated_at,
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
