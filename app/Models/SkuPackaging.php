<?php

namespace App\Models;  
use App\Models\SkuPackagingCategory;
use App\Models\SkuPackagingSubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuPackaging extends Model
{
    protected $table = 'sku_packaging';  
    protected $fillable = ['sku_packaging','position','status'];

    public function sku_packaging_categories()
    {
        return $this->hasmany(SkuPackagingCategory::class,'sku_packaging_id','id');
    }

    public function sku_packaging_subcategories()
    {
        return $this->hasmany(SkuPackagingSubcategory::class,'sku_packaging_id','id');
    }
    

}
