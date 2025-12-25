<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductImage;
use App\Models\CartItem;

class ProductVariant extends Model
{
      use HasFactory;
      use SoftDeletes;
        public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function orderItems():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

     public function images():HasMany
    {
        return $this->hasMany(ProductImage::class);
    }


    public function cartItems():HasMany
    {
        return $this->hasMany(CartItem::class);
    }

}
