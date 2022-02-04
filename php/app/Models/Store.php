<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Store
 * @package App\Models
 */
class Store extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'api_key',
        'shop_name',
        'api_password',
        'api_secret',
        'language',
        'currency',
        'location_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function items()
    {
        return $this->morphedByMany(Item::class, 'shopify')
            ->withPivot(['external_id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function variants()
    {
        return $this->morphedByMany(Variant::class, 'shopify')
            ->withPivot(['external_id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
