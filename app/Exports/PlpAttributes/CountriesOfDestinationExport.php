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
use App\Models\CountriesOfDestination; 
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class CountriesOfDestinationExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
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
        $sort='countries_of_destination.created_at';  
        $sortBy='DESC';     
        $countries_of_destination = CountriesOfDestination::with('countries_of_destination_subcategories.subcategory')->where('status','!=',2)->orderBy($sort,$sortBy); 
        $array = $countries_of_destination->pluck('id')->toArray(); 
        Session::put('data', $array);  
        return $countries_of_destination;
    }
    public function map($countries_of_destination): array
    {    
        $countries_of_destination_subcategories = Helper::GetCountriesOfDestinationSubcategories($countries_of_destination->id); 
        $countries_of_destination_categories = Helper::GetCountriesOfDestinationProductCategories($countries_of_destination->id);   
        $data = Session::get('data'); 
        if($countries_of_destination->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($countries_of_destination->id, $data)+1, 
            $countries_of_destination->country_short,
            $countries_of_destination->country, 
            $countries_of_destination->position, 
            $status,   
            implode(', ', $countries_of_destination_subcategories),
            implode(', ', $countries_of_destination_categories),
            $countries_of_destination->created_at,
            $countries_of_destination->updated_at,
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
