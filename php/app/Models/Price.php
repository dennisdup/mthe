<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Price
 *
 * @package App\Models
 */
class Price extends Model
{
    protected $fillable = [
        'price_level',
        'price_list',
        'price',
        'currency',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
