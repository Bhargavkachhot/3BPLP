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
use App\Models\SkuPackaging; 
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class SkuPackagingExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'SKU Packaging',  
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
        $sort='sku_packaging.created_at';  
        $sortBy='DESC';     
        $sku_packaging = SkuPackaging::with('sku_packaging_subcategories.subcategory')->where('status','!=',2)->orderBy($sort,$sortBy); 
        $array = $sku_packaging->pluck('id')->toArray(); 
        Session::put('data', $array);  
        return $sku_packaging;
    }
    public function map($sku_packaging): array
    {    
        $sku_packaging_subcategories = Helper::GetSkuPackagingSubcategories($sku_packaging->id); 
        $sku_packaging_categories = Helper::GetSkuPackagingCategories($sku_packaging->id);   
        $data = Session::get('data'); 
        if($sku_packaging->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($sku_packaging->id, $data)+1, 
            $sku_packaging->sku_packaging,
            $sku_packaging->position, 
            $status,   
            implode(', ', $sku_packaging_subcategories),
            implode(', ', $sku_packaging_categories),
            $sku_packaging->created_at,
            $sku_packaging->updated_at,
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
