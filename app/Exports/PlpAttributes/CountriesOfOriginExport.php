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
use App\Models\CountriesOfOrigin; 
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class CountriesOfOriginExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Country Short',  
            'Country',  
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
        $sort='countries_of_origin.created_at';  
        $sortBy='DESC';     
        $countries_of_origin = CountriesOfOrigin::with('countries_of_origin_subcategories.subcategory')->where('status','!=',2)->orderBy($sort,$sortBy); 
        $array = $countries_of_origin->pluck('id')->toArray(); 
        Session::put('data', $array);  
        return $countries_of_origin;
    }
    public function map($countries_of_origin): array
    {    
        $countries_of_origin_subcategories = Helper::GetCountriesOfOriginSubcategories($countries_of_origin->id); 
        $countries_of_origin_categories = Helper::GetCountriesOfOriginProductCategories($countries_of_origin->id);   
        $data = Session::get('data'); 
        if($countries_of_origin->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($countries_of_origin->id, $data)+1, 
            $countries_of_origin->country_short,
            $countries_of_origin->country, 
            $countries_of_origin->position, 
            $status,   
            implode(', ', $countries_of_origin_subcategories),
            implode(', ', $countries_of_origin_categories),
            $countries_of_origin->created_at,
            $countries_of_origin->updated_at,
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
