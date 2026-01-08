<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\category;
use App\Models\Brand;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Wishlist;
use App\Models\Review;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

     public function category():BelongsTo
    {
        return $this->belongsTo(category::class);
    }


    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }


    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }


    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
