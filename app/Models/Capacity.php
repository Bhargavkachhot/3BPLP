<?php

namespace App\Models;  
use App\Models\CapacityProductCategory;
use App\Models\CapacitySubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacity extends Model
{
    protected $table = 'capacities';  
    protected $fillable = ['capacity','position','status'];

    public function capacity_product_categories()
    {
        return $this->hasmany(CapacityProductCategory::class,'capacity_id','id');
    }

    public function capacity_subcategories()
    {
        return $this->hasmany(CapacitySubcategory::class,'capacity_id','id');
    }
    

}
