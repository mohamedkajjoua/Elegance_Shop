<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $fillable = [
        'site_name',
        'site_description',
        'call_us',
        'email_us',
        'visit_us',
        'instagram',
        'facebook',
        'x'
    ];
}
