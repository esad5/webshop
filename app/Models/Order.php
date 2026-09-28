<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'items',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }
}