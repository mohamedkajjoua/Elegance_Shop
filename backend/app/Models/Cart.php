<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\CartItem;
class Cart extends Model
{
     use HasFactory;
     use SoftDeletes;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
