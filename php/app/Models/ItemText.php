<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ItemText
 * @package App\Models
 */
class ItemText extends Model
{
    public const LANGUAGE_DU = '01';
    public const LANGUAGE_EN = '02';

    public const LANGUAGES = [
        self::LANGUAGE_DU => 'dk',
        self::LANGUAGE_EN => 'en',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'text',
        'language',
    ];

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function setTextAttribute($value)
    {
        $this->attributes['text'] = $value ? $value : '';
    }
}
