<?php

namespace App\Models;  
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryPackagingSubcategory extends Model
{
    protected $table = 'primary_packaging_subcategories';
    protected $fillable = ['primary_packaging_id','product_subcategory_id'];   

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'product_subcategory_id','id');
    }  
    
}
