<?php

namespace App\Models;   
use App\Models\PrimaryPackagingAttributeId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryPackagingAttribute extends Model
{
    protected $table = 'primary_packaging_attributes';  
    protected $fillable = ['primary_packaging_attributes','position','status'];

    public function primary_packaging_attribute_ids()
    {
        return $this->hasmany(PrimaryPackagingAttributeId::class,'primary_packaging_attribute_id','id');
    } 

}
