<?php

namespace App\Exports\PlpCategories;

use App\Models\Seo;  
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Session; 
use App\Models\SubCategory; 
use App\Models\PrimaryCategory;


class SubcategoryExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Primary Category',   
            'Artical Number',  
            'Subcategory',
            'URL key',
            'Full URL key ',
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
        $subcategories = SubCategory::with('seo')->where('status','!=',2);
        $array = $subcategories->pluck('id')->toArray();
        Session::put('data', $array); 
        return $subcategories;
    }
    public function map($subcategories): array
    {  
        $primary_category = PrimaryCategory::where('id',$subcategories->primary_category_id)->pluck('category_name')->first();
        if($subcategories->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        $data = Session::get('data'); 
        return [
            array_search ($subcategories->id, $data)+1,
            $primary_category, 
            $subcategories->artical_number,
            $subcategories->subcategory,
            $subcategories->url_key,
            $subcategories->full_url_key,
            $subcategories->position,  
            $status, 
            $subcategories->meta_title,
            $subcategories->meta_description, 
            $subcategories->description, 
            isset($subcategories['seo']['seo_headline_one']) ? $subcategories['seo']['seo_headline_one']: '-',
            isset($subcategories['seo']['seo_description_one']) ? $subcategories['seo']['seo_description_one']: '-',
            isset($subcategories['seo']['seo_headline_two']) ? $subcategories['seo']['seo_headline_two']: '-',
            isset($subcategories['seo']['seo_description_two']) ? $subcategories['seo']['seo_description_two']: '-',
            isset($subcategories['seo']['seo_headline_three']) ? $subcategories['seo']['seo_headline_three']: '-',
            isset($subcategories['seo']['seo_description_three']) ? $subcategories['seo']['seo_description_three']: '-',
            isset($subcategories['seo']['seo_description_other']) ? $subcategories['seo']['seo_description_other']: '-',
            $subcategories->created_at,
            $subcategories->updated_at,
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
