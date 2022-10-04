<?php

namespace App\Models;  
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeSubcategory extends Model
{
    protected $table = 'product_attributes_subcategories';
    protected $fillable = ['product_attribute_id','product_subcategory_id'];   

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'product_subcategory_id','id');
    }  
    
}
