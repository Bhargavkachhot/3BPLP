<?php

namespace App\Models;  
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuPackagingSubcategory extends Model
{
    protected $table = 'sku_packaging_subcategories';
    protected $fillable = ['sku_packaging_id','product_subcategory_id'];   

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'product_subcategory_id','id');
    }  
    
}
