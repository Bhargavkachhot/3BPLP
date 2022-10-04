<?php

namespace App\Exports\PlpCategories;

use App\Models\Seo;  
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Session;

use App\Models\ProductCategory;


class ProductCategoriesExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
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
            'Artical Number', 
            'Product Category', 
            'URL key',
            'Full URL key',
            'Position', 
            'Status',
            'Meta title',
            'Meta description',
            'Description', 
            'SEO headline one',
            'SEO description one',
            'SEO headline two',
            'SEO description two',
            'SEO headline three',
            'SEO description three',
            'SEO description other',
            'Created at',
            'Updated at' 
        ];
    } 
    public function query()
    {   
        $sort='example_products.created_at';  
        $sortBy='DESC'; 
        $product_categories = ProductCategory::with('seo')->where('status','!=',2);
        $array = $product_categories->pluck('id')->toArray();
        Session::put('data', $array); 
        return $product_categories;
    }
    public function map($product_categories): array
    {   
        $primary_category = PrimaryCategory::where('id',$product_categories->primary_category_id)->pluck('category_name')->first();
        $subcategory = SubCategory::where('id',$product_categories->subcategory_id)->select('subcategory','artical_number')->first(); 
        if($product_categories->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        $data = Session::get('data'); 
        return [
            array_search ($product_categories->id, $data)+1,
            isset($primary_category) ? $primary_category : '-', 
            isset($subcategory) ? $subcategory->artical_number.' '.$subcategory->subcategory : '-', 
            $product_categories->artical_number, 
            $product_categories->product_category,  
            $product_categories->url_key,
            $product_categories->full_url_key, 
            $product_categories->position,   
            $status,
            $product_categories->meta_title,
            $product_categories->meta_description, 
            $product_categories->description,
            isset($product_categories['seo']['seo_headline_one']) ? $product_categories['seo']['seo_headline_one']: '-',
            isset($product_categories['seo']['seo_description_one']) ? $product_categories['seo']['seo_description_one']: '-',
            isset($product_categories['seo']['seo_headline_two']) ? $product_categories['seo']['seo_headline_two']: '-',
            isset($product_categories['seo']['seo_description_two']) ? $product_categories['seo']['seo_description_two']: '-',
            isset($product_categories['seo']['seo_headline_three']) ? $product_categories['seo']['seo_headline_three']: '-',
            isset($product_categories['seo']['seo_description_three']) ? $product_categories['seo']['seo_description_three']: '-',
            isset($product_categories['seo']['seo_description_other']) ? $product_categories['seo']['seo_description_other']: '-', 
            $product_categories->created_at,
            $product_categories->updated_at,
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
