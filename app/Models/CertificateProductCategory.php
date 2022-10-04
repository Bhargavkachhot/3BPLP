<?php

namespace App\Models; 
use App\Models\ProductCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateProductCategory extends Model
{
    protected $table = 'certificates_product_categories';
    protected $fillable = ['certificate_id','product_category_id'];    
    
    public function productcategory()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }   
}
