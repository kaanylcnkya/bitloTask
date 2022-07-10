<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Company extends Model
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'site_url',
        'name',
        'lastname',
        'company_name',
        'email',
        'password',
    ];
}
