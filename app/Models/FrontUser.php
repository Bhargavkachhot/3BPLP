<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Models\FrontUserRole;

class FrontUser extends Authenticatable
{ 
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $table = "user_front"; 
    protected $fillable = [ 
        'name', 
        'phone_number', 
        'email', 
        'password', 
        'company_size', 
        'company_address',
        'zip',  
        'country', 
        'profile_picture',  
        'vat_number', 
        'product_category', 
        'status', 
        'user_role', 
        'email_verified', 
        'remember_token', 
        'created_at', 
        'updated_at', 
    ];  

}
