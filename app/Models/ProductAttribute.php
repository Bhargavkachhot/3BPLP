<?php

namespace App\Models;  
use App\Models\ProductAttributeCategory;
use App\Models\ProductAttributeSubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';  
    protected $fillable = ['product_attribute','position','status'];

    public function product_attributes_categories()
    {
        return $this->hasmany(ProductAttributeCategory::class,'product_attribute_id','id');
    }

    public function product_attributes_subcategories()
    {
        return $this->hasmany(ProductAttributeSubcategory::class,'product_attribute_id','id');
    }
    

}
