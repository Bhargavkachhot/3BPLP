<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';

    protected $fillable = [
        'email', 
        'date_format', 
        'website_logo', 
        'created_at', 
        'updated_at', 
        'phone', 
        'currency',
        'admin_commision',
        'android_version',
        'ios_version', 
        'service_charge', 
        'facebook_link', 
        'instagram_link', 
        'twitter_link', 
        'android_link', 
        'smtpport', 
        'smtphost', 
        'smtpusername', 
        'smtppassword', 
        'from_name', 
        'from_email',
        'support_email'

    ];
}
