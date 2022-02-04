<?php

namespace App\Services\Importer\Builders;

use App\Models\Store;
use App\Models\Variant as VariantModel;
use App\Services\Importer\Objects\Option;
use App\Services\Importer\Objects\Variant as VariantObject;

/**
 * Class Variant
 *
 * @package App\Services\Importer\Builders
 */
class Variant
{
    /**
     * @var
     */
    protected $store;

    /**
     * @var VariantModel
     */
    protected $variant;

    /**
     * Variant constructor.
     *
     * @param Store        $store
     * @param VariantModel $variant
     */
    public function __construct(Store $store, VariantModel $variant)
    {
        $this->store   = $store;
        $this->variant = $variant;
    }

    /**
     * @return VariantObject
     */
    public function getVariant(): VariantObject
    {
        $variant = (new VariantObject())
            ->setCount($this->variant->stock->quantity ?? 0)
            ->setPrice(($price = $this->variant->storePrice($this->store)->first()) ? $price->price : 0)
            ->setSku($this->variant->external_id)
            ->setEan($this->variant->ean);

        $this->setOptions($variant);

        $this->setIdentifier($variant);

        return $variant;
    }

    /**
     * @param VariantObject $variant
     *
     * @return void
     */
    protected function setOptions(VariantObject $variant): void
    {
        if ($this->variant->size) {
            $sizeOption = (new Option())
                ->setName('size')
                ->setValue($this->variant->size);

            $variant->addOption($sizeOption);
        }

        if ($this->variant->colour_text) {
            $colourOption = (new Option())
                ->setName('color')
                ->setValue($this->variant->colour_text);

            $variant->addOption($colourOption);
        }
    }

    /**
     * @param VariantObject $variant
     */
    protected function setIdentifier(VariantObject $variant): void
    {
        $shopifyStore = $this->variant->store()->wherePivot('store_id', $this->store->id)->first();

        if ($shopifyStore) {
            $variant->setId($shopifyStore->pivot->external_id);
            $variant->setInventoryLevel($shopifyStore->pivot->inventory_item_id);
        }
    }
}
