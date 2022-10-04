<?php

namespace App\Models;  
use App\Models\PrimaryPackagingCategory;
use App\Models\PrimaryPackagingSubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryPackaging extends Model
{
    protected $table = 'primary_packaging';  
    protected $fillable = ['primary_packaging','position','status'];

    public function primary_packaging_categories()
    {
        return $this->hasmany(PrimaryPackagingCategory::class,'primary_packaging_id','id');
    }

    public function primary_packaging_subcategories()
    {
        return $this->hasmany(PrimaryPackagingSubcategory::class,'primary_packaging_id','id');
    }
    

}
