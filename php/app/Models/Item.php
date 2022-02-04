<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Item
 * @package App\Models
 */
class Item extends Model
{
    public const MORPH_TYPE = 1;

    /**
     * @var array
     */
    protected $fillable = [
        'external_id',
        'bran_number',
        'style',
        'quality',
        'text',
        'brand',
        'brand_text',
        'group',
        'group_text',
        'comp_code',
        'comp_text',
        'image_2',
        'item_color',
        'pic_color',
        'pic_style',
        'item_color_2',
        'item_color_3',
        'item_color_4',
        'item_color_5',
        'item_color_6',
        'item_color_7',
        'item_color_8',
        'item_color_9',
        'item_color_10',
        'item_color_text',
        'collection',
        'collection_name',
        'new',
        'active',
    ];

    /**
     * @return BelongsTo
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    /**
     * @return HasMany
     */
    public function itemTexts(): HasMany
    {
        return $this->hasMany(ItemText::class);
    }

    /**
     * @param Store $store
     * @return mixed
     */
    public function storeItemText(Store $store): HasMany
    {
        return $this->itemTexts()->where('store_id', $store->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function store()
    {
        return $this->morphToMany(Store::class, 'shopify')
            ->withPivot(['external_id']);
    }
}
