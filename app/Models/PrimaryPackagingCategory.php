<?php

namespace App\Models; 
use App\Models\ProductCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryPackagingCategory extends Model
{
    protected $table = 'primary_packaging_categories';
    protected $fillable = ['primary_packaging_id','product_category_id'];    
    
    public function productcategory()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }   
}
