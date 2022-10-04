<?php

namespace App\Models; 
use App\Models\ProductCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeCategory extends Model
{
    protected $table = 'product_attributes_categories';
    protected $fillable = ['product_attribute_id','product_category_id'];    
    
    public function productcategory()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }   
}
