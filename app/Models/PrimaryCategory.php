<?php

namespace App\Models;
use App\Models\Seo; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryCategory extends Model
{
    protected $table = 'primary_categories'; 

    public function seo()
    {
        return $this->hasone(Seo::class,'primary_category_id','id');
    } 

}
