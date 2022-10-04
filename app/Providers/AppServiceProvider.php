<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        Validator::extend('encoded_unique', function ($attribute, $value, $parameters, $validator) {
            if ( !empty($parameters) && count($parameters) > 0 )
            {
                $table = $parameters[0];
                $field = @$parameters[1] ? $parameters[1] : $attribute;
                $except_field = @$parameters[2];
                $except_value = @$parameters[3];
                $except_field2 = @$parameters[4];
                $except_value2 = @$parameters[5];

                $query = DB::table($table);
                if($except_field && $except_value) 
                    $query->where($except_field, '!=', $except_value);
                if($except_field2 && $except_value2) 
                    $query->where($except_field2, '!=', $except_value2);
                if($value)
                    $query->where($field,'=',urlencode($value));
                return ($query->exists())?false:true;
            }

            return true;
        });

    }
    
}
