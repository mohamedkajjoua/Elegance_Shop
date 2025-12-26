<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cart;
use App\Models\ProductVariant;

class CartItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }


    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
