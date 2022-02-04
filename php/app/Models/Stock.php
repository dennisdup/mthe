<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Stock
 *
 * @package App\Models
 */
class Stock extends Model
{
    protected $fillable = [
        'next_week',
        'next_quantity',
        'quantity',
        'updated_at',
    ];
}
