<?php

namespace App\Services\Export\DTO;

use App\Models\Item;
use App\Models\Variant;

/**
 * Class VariantDataTransferObject
 * @package App\Services\Export\DTO
 */
class VariantDataTransferObject extends DataTransferObject
{
    /**
     * @param array $entity
     * @return \Illuminate\Database\Eloquent\Model|void
     */
    public function getEntity(array $entity)
    {
        $model = parent::getEntity($entity);

        $item = Item::where('external_id', $entity['item'])->first();

        if ($item) {
            $item->variants()->save($model);
        }

        return $model;
    }

    /**
     * @param array $entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getModel(array $entity)
    {
        if ($model = Variant::where('external_id', $entity['variant'])->first()) {
            return $model;
        }

        return parent::getModel($entity);
    }
}
