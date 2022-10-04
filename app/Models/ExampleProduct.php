<?php

namespace App\Models;
use App\Models\Seo;
use App\Models\PrimaryCategory;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleProduct extends Model
{
    protected $table = 'example_products';  

    public function primary_category()
    {
        return $this->belongsTo(PrimaryCategory::class,'primary_category_id','id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id','id');
    }

    public function productcategory()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }
    

}
