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
use App\Models\PrimaryPackaging; 
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class PrimaryPackagingExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Primary Packaging',  
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
        $sort='primary_packaging.created_at';  
        $sortBy='DESC';     
        $primary_packaging = PrimaryPackaging::with('primary_packaging_subcategories.subcategory')->where('status','!=',2); 
        $array = $primary_packaging->orderBy($sort,$sortBy)->pluck('id')->toArray(); 
        Session::put('data', $array);  
        return $primary_packaging;
    }
    public function map($primary_packaging): array
    {    
        $primary_packaging_subcategories = Helper::GetPrimaryPackagingSubcategories($primary_packaging->id); 
        $primary_packaging_categories = Helper::GetPrimaryPackagingCategories($primary_packaging->id);   
        $data = Session::get('data'); 
        if($primary_packaging->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($primary_packaging->id, $data)+1, 
            $primary_packaging->primary_packaging,
            $primary_packaging->position, 
            $status,   
            implode(', ', $primary_packaging_subcategories),
            implode(', ', $primary_packaging_categories),
            $primary_packaging->created_at,
            $primary_packaging->updated_at,
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
