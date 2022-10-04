<?php

namespace App\Models; 
use App\Models\ProductCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountriesOfDestinationProductCategory extends Model
{
    protected $table = 'countries_of_destination_product_categories';
    protected $fillable = ['countries_of_destination_id','product_category_id'];    
    
    public function productcategory()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }   
}
