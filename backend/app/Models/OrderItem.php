<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Product;


class OrderItem extends Model
{
      use HasFactory;
      use SoftDeletes;
      
       protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'price',
        'subtotal'
    ];
        public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariant():BelongsTo
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_id');
    }

      public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
