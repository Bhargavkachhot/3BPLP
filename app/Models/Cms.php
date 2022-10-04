<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cms extends Model {

	protected $table = 'cms';

	protected $fillable = [
        'page_title', 'page_content' , 'page_title_g' , 'page_content_g' , 'page_title_h', 'page_content_h', 'meta_title', 'meta_description' , 'status','created_at' , 'updated_at'];

    // public function childdata()
    // {
    //     return $this->hasMany('App\Models\Cms','id');
    // }
}