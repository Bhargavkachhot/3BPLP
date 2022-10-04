<?php

namespace App\Models;  
use App\Models\CountriesOfOriginProductCategory;
use App\Models\CountriesOfOriginSubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountriesOfOrigin extends Model
{
    protected $table = 'countries_of_origin';  
    protected $fillable = ['country','country_short','position','status'];

    public function countries_of_origin_product_categories()
    {
        return $this->hasmany(CountriesOfOriginProductCategory::class,'countries_of_origin_id','id');
    }

    public function countries_of_origin_subcategories()
    {
        return $this->hasmany(CountriesOfOriginSubcategory::class,'countries_of_origin_id','id');
    }
    

}
