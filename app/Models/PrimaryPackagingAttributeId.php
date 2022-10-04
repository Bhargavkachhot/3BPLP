<?php

namespace App\Models; 
use App\Models\PrimaryPackagingAttribute; 
use App\Models\PrimaryPackaging; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryPackagingAttributeId extends Model
{
    protected $table = 'primary_packaging_attribute_ids';
    protected $fillable = ['primary_packaging_attribute_id','primary_packaging_id'];    
    
    public function primary_packaging_attributes()
    {
        return $this->belongsTo(PrimaryPackagingAttribute::class,'primary_packaging_attribute_id','id');
    } 

    public function primary_packaging()
    {
        return $this->belongsTo(PrimaryPackaging::class,'primary_packaging_id','id');
    }    
}
