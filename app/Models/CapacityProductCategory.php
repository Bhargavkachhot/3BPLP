<?php

namespace App\Models; 
use App\Models\ProductCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapacityProductCategory extends Model
{
    protected $table = 'capacities_product_categories';
    protected $fillable = ['capacity_id','product_category_id'];    
    
    public function productcategory()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }   
}
