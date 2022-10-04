<?php

namespace App\Exports\PlpCategories;

use App\Models\ProductCategory;
use App\Models\ExampleProduct;  
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Session; 

class ExampleProductExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Primary Category', 
            'Subcategory', 
            'Product Category', 
            'Example Product',  
            'Position', 
            'Status',
            'Created at',
            'Updated at' 
        ];
    } 
    public function query()
    {   
        $sort='example_products.created_at';  
        $sortBy='DESC'; 
        $example_product = ExampleProduct::with('primary_category','subcategory','productcategory')->where('status','!=',2)->orderBy($sort,$sortBy);
        $array = $example_product->pluck('id')->toArray(); 
        Session::put('data', $array); 
        return $example_product;
    }
    public function map($example_product): array
    {   
        $primary_category = $example_product['primary_category']->category_name;
        $subcategory =  $example_product['subcategory']->subcategory; 
        $product_category =  $example_product['productcategory']->artical_number.' '.$example_product['productcategory']->product_category;
        if($example_product->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }      
        $data = Session::get('data'); 
        return [
            array_search ($example_product->id, $data)+1,
            isset($primary_category) ? $primary_category : '-',
            isset($subcategory) ? $subcategory : '-',
            isset($product_category) ? $product_category : '-',   
            $example_product->example_product,  
            $example_product->position, 
            $status,  
            $example_product->created_at,
            $example_product->updated_at,
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
