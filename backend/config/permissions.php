<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permissions Structure
    |--------------------------------------------------------------------------
    | Format: 'module.action'
    | Wildcard Support: '*' means all permissions.
    | 'module.*' means all actions within that module.
    */

    'roles' => [
        // 1. Admin: 
        'admin' => [
            '*',
        ],

        // 2. Editor: 
        'editor' => [
            'products.view',
            'products.create',
            'products.update',
            'products.delete',

            'brands.view',
            'brands.create',
            'brands.update',

            'categories.view',
            'categories.create',
            'categories.update',

            'reviews.view',
            'reviews.update',

            'users.view',
        ],

        // 3. Customer: 
        'customer' => [
            'products.view',
            'categories.view',
            'brands.view',
            'reviews.create',
            'reviews.view',
            'orders.create',
            'orders.view',
            'addresses.create',
            'addresses.view',
            'addresses.update',
        ],
    ],
];
