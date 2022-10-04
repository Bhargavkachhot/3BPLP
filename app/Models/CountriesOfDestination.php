<?php

namespace App\Models;  
use App\Models\CountriesOfDestinationProductCategory;
use App\Models\CountriesOfDestinationSubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountriesOfDestination extends Model
{
    protected $table = 'countries_of_destination';  
    protected $fillable = ['country','country_short','position','status'];

    public function countries_of_destination_product_categories()
    {
        return $this->hasmany(CountriesOfDestinationProductCategory::class,'countries_of_destination_id','id');
    }

    public function countries_of_destination_subcategories()
    {
        return $this->hasmany(CountriesOfDestinationSubcategory::class,'countries_of_destination_id','id');
    }
    

}
