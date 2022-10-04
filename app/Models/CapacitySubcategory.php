<?php

namespace App\Models;  
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapacitySubcategory extends Model
{
    protected $table = 'capacities_subcategories';
    protected $fillable = ['capacity_id','subcategory_id'];   

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id','id');
    }  
    
}
