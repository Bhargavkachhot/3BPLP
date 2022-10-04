<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    
    protected $table = 'email_template';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title', 'subject', 'content', 'status', 'created_date', 'updated_date' 
    ];

    // public function childdata()
    // {
    //     return $this->hasMany('App\Models\EmailTemplate', 'parent_id', 'id');
    // }

}
