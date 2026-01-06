<?php

namespace App\Services\User;


use App\Models\category;

class HomeService
{

    public function getHomeCategories()
    {

        return category::withCount([
            'products as products_count' => function ($query) {
                $query->where('is_active', 1);
            }
        ])
            ->whereHas('products', function ($query) {
                $query->where('is_active', 1);
            })
            ->limit(4)
            ->get();
    }
}
