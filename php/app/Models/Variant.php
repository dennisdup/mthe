<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Variant
 * @package App\Models
 */
class Variant extends Model
{
    public const MORPH_TYPE = 2;

    /**
     * @var array
     */
    protected $fillable = [
        'item_id',
        'external_id',
        'colour_code',
        'colour_text',
        'size_no',
        'size',
        'price_level',
        'activ_colour',
        'ean',
        'item_style',
        'pic_color',
        'item_text',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function price()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * @param Store $store
     * @return mixed
     */
    public function storePrice(Store $store)
    {
        return $this->hasMany(Price::class)->whereStoreId($store->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function store()
    {
        return $this->morphToMany(Store::class, 'shopify')
            ->withPivot(['external_id','inventory_item_id']);
    }
}
