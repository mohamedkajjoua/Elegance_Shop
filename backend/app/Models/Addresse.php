<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\User;

class Addresse extends Model
{
     use HasFactory;
      use SoftDeletes;

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders():hasMany
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }
}
