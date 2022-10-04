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
use App\Models\ProductAttribute; 
use App\Models\PrimaryPackagingAttribute;
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class PrimaryPackagingAttributeExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Primary Packaging Attributes',  
            'Position',
            'Status',
            'Primary Packaging', 
            'Created at',
            'Updated at' 
        ];
    } 
    public function query()
    {   
        $sort='primary_packaging_attributes.created_at';  
        $sortBy='DESC';   
        $primary_packaging_attributes = PrimaryPackagingAttribute::with('primary_packaging_attribute_ids.primary_packaging')->where('status','!=',2)->orderBy($sort,$sortBy);   
        $array = $primary_packaging_attributes->pluck('id')->toArray();
        Session::put('data', $array); 
        return $primary_packaging_attributes;
    }
    public function map($primary_packaging_attributes): array
    {   
        $primary_packaging = [];
        if(isset($primary_packaging_attributes->primary_packaging_attribute_ids) && count($primary_packaging_attributes->primary_packaging_attribute_ids) > 0){ 

            foreach ($primary_packaging_attributes->primary_packaging_attribute_ids as $key => $value) {
                $primary_packaging[] =  $value->primary_packaging->primary_packaging;
            }
        }else{  
            $primary_packaging[] =  null; 
        }   
        $data = Session::get('data'); 
        if($primary_packaging_attributes->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($primary_packaging_attributes->id, $data)+1, 
            $primary_packaging_attributes->primary_packaging_attribute,
            $primary_packaging_attributes->position, 
            $status,   
            implode(', ', $primary_packaging), 
            $primary_packaging_attributes->created_at,
            $primary_packaging_attributes->updated_at,
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
