<?php

namespace App\Models;  
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateSubcategory extends Model
{
    protected $table = 'certificates_subcategories';
    protected $fillable = ['certificate_id','subcategory_id'];   

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id','id');
    }  
    
}
