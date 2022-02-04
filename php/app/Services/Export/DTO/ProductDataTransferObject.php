<?php

namespace App\Services\Export\DTO;

use App\Models\Item;

/**
 * Class ProductDataTransferObject
 * @package App\Services\Export\DTO
 */
class ProductDataTransferObject extends DataTransferObject
{
    /**
     * @param array $entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getModel(array $entity)
    {
        if ($model = Item::where('external_id', $entity['item'])->first()) {
            return $model;
        }

        return parent::getModel($entity);
    }
}
