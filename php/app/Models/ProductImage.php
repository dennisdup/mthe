<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductImage
 * @package App\Models
 */
class ProductImage extends Model
{
    protected $table = 'product_image';

    protected $fillable = [
        'img_name',
        'position',
        'last_modified',
        'product_id',
    ];
}
