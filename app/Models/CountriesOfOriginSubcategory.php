<?php

namespace App\Models;  
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountriesOfOriginSubcategory extends Model
{
    protected $table = 'countries_of_origin_subcategories';
    protected $fillable = ['countries_of_origin_id','product_subcategory_id'];   

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'product_subcategory_id','id');
    }  
    
}
