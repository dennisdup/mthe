<?php

namespace App\Services\Export\DTO;

use App\Models\Variant;

/**
 * Class StockDataTransferObject
 * @package App\Services\Export\DTO
 */
class StockDataTransferObject extends DataTransferObject
{
    /**
     * @param array $entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity(array $entity)
    {
        $model = parent::getEntity($entity);

        $variant = Variant::where('external_id', $entity['variantid'])->first();

        if (!$variant) {
            return;
        }

        $variant->stock()->delete();
        $variant->stock()->save($model);

        return $model;
    }
}
