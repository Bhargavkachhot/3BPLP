<?php

namespace App\Models;
use App\Models\Seo;
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories'; 

    public function seo()
    {
        return $this->hasone(Seo::class,'product_category_id','id');
    } 

    public function primary_category()
    {
        return $this->belongsTo(PrimaryCategory::class,'primary_category_id','id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id','id');
    }
    

}
