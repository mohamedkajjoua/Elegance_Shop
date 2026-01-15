<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'status',
        'gateway_response',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
