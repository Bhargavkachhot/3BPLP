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
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class ProductAttributesExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Product Attribute',  
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
        $sort='created_at';  
        $sortBy='DESC';    
        $product_attributes = ProductAttribute::with('product_attributes_subcategories.subcategory')->where('status','!=',2);
        $array = $product_attributes->orderBy($sort,$sortBy)->pluck('id')->toArray();
        Session::put('data', $array); 
        return $product_attributes;
    }
    public function map($product_attributes): array
    {   
        $product_attributes_subcategories = Helper::GetProductSubcategories($product_attributes->id); 
        $product_attributes_categories = Helper::GetProductCategories($product_attributes->id);  
        $data = Session::get('data'); 
        if($product_attributes->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($product_attributes->id, $data)+1, 
            $product_attributes->product_attribute,
            $product_attributes->position, 
            $status,   
            implode(', ', $product_attributes_subcategories),
            implode(', ', $product_attributes_categories),
            $product_attributes->created_at,
            $product_attributes->updated_at,
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
