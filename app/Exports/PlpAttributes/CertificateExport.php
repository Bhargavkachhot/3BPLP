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
use App\Models\Certificate; 
use App\Models\SubCategory; 
use App\Models\ProductCategory;

class CertificateExport implements FromQuery,WithHeadings, ShouldAutoSize, WithEvents, WithMapping 
{
    private $i = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id', 
            'Certificate', 
            'Description',  
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
        $certificates = Certificate::with('certificate_subcategories.subcategory')->where('status','!=',2)->orderBy($sort,$sortBy); 
        $array = $certificates->pluck('id')->toArray(); 
        Session::put('data', $array);  
        return $certificates;
    }
    public function map($certificates): array
    {    
        $certificate_subcategories = Helper::GetCertificateSubcategories($certificates->id); 
        $certificates_categories = Helper::GetCertificateProductCategories($certificates->id);   
        $data = Session::get('data'); 
        if($certificates->status == 1){
            $status = 'Active';
        }else{
            $status = 'Inactive';
        }
        return [
            array_search ($certificates->id, $data)+1,  
            $certificates->certificate, 
            $certificates->description, 
            $certificates->position, 
            $status,   
            implode(', ', $certificate_subcategories),
            implode(', ', $certificates_categories),
            $certificates->created_at,
            $certificates->updated_at,
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
