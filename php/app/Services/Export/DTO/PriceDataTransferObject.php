<?php

namespace App\Services\Export\DTO;

use App\Models\Price;
use App\Models\Store;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PriceDataTransferObject
 * @package App\Services\Export\DTO
 */
class PriceDataTransferObject extends DataTransferObject
{
    /**
     * @var Store[]|Collection
     */
    private static $stores;

    /**
     * @param array $entity
     *
     * @return \Illuminate\Database\Eloquent\Model|void
     */
    public function getEntity(array $entity)
    {
        /** @var Price $model */
        $model = parent::getEntity($entity);

        $variant = Variant::where('external_id', $entity['variantid'])->first();

        if (!$variant) {
            return;
        }

        $stores = $this->getStores()->where('currency', $entity['currency']);

        $stores->each(function (Store $store) use ($variant, $model) {
            $this->assign($store, $variant, clone $model);
        });
    }

    /**
     * @return Store[]|Collection
     */
    public function getStores()
    {
        if (!$this::$stores) {
            $this::$stores = Store::all();
        }

        return $this::$stores;
    }

    /**
     * @param Store $store
     * @param Variant $variant
     * @param Price $price
     */
    protected function assign(Store $store, Variant $variant, Price $price): void
    {
        if (Price::where(['store_id' => $store->id, 'variant_id' => $variant->id])->exists()) {
            return;
        }

        $price->store()->associate($store);
        $variant->storePrice($store)->delete();

        $variant->price()->save($price);
    }
}
