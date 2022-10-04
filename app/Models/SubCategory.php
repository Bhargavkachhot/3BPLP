<?php

namespace App\Models;
use App\Models\Seo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'subcategories'; 

    public function seo()
    {
        return $this->hasone(Seo::class,'subcategory_id','id');
    }

}
