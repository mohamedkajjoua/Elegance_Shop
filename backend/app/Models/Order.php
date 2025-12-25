<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Review;



class Order extends Model
{
      use HasFactory;
        use SoftDeletes;


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

      public function shippingAddress():BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

     public function orderItems():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

     public function paymentTransactions():HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

        public function reviews():HasMany
    {
        return $this->hasMany(Review::class);
    }
}
