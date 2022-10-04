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

use App\Models\PrimaryCategory;


class PrimaryCategoriesExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Category Name',  
            'URL key',
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
        $primary_categories = PrimaryCategory::with('seo')->where('status','!=',2);
        $array = $primary_categories->pluck('id')->toArray();
        Session::put('data', $array); 
        return $primary_categories;
    }
    public function map($primary_categories): array
    {    
        $data = Session::get('data'); 
        if($primary_categories->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($primary_categories->id, $data)+1,
            $primary_categories->category_name, 
            $primary_categories->url_key,
            $primary_categories->position, 
            $status,  
            $primary_categories->meta_title,
            $primary_categories->meta_description, 
            $primary_categories->description,
            isset($primary_categories['seo']['seo_headline_one']) ? $primary_categories['seo']['seo_headline_one']: '-',
            isset($primary_categories['seo']['seo_description_one']) ? $primary_categories['seo']['seo_description_one']: '-',
            isset($primary_categories['seo']['seo_headline_two']) ? $primary_categories['seo']['seo_headline_two']: '-',
            isset($primary_categories['seo']['seo_description_two']) ? $primary_categories['seo']['seo_description_two']: '-',
            isset($primary_categories['seo']['seo_headline_three']) ? $primary_categories['seo']['seo_headline_three']: '-',
            isset($primary_categories['seo']['seo_description_three']) ? $primary_categories['seo']['seo_description_three']: '-',
            isset($primary_categories['seo']['seo_description_other']) ? $primary_categories['seo']['seo_description_other']: '-', 
            $primary_categories->created_at,
            $primary_categories->updated_at,
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
