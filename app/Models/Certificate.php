<?php

namespace App\Models;  
use App\Models\CertificateProductCategory;
use App\Models\CertificateSubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'certificates';  
    protected $fillable = ['certificate','description','position','status'];

    public function certificate_product_categories()
    {
        return $this->hasmany(CertificateProductCategory::class,'certificate_id','id');
    }

    public function certificate_subcategories()
    {
        return $this->hasmany(CertificateSubcategory::class,'certificate_id','id');
    }
    

}
