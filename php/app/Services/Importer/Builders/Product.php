<?php

namespace App\Services\Importer\Builders;

use App\Models\Item;
use App\Models\Store;
use App\Services\Importer\Objects\Product as ProductObject;

/**
 * Class Product
 * @package App\Services\Importer\Builders
 */
class Product
{
    /**
     * @var Store
     */
    protected $store;

    /**
     * @var Item
     */
    protected $item;

    /**
     * Product constructor.
     * @param Store $store
     * @param Item $item
     */
    public function __construct(Store $store, Item $item)
    {
        $this->store = $store;
        $this->item = $item;
    }

    /**
     * @return ProductObject
     */
    public function getProduct(): ProductObject
    {
        $product = (new ProductObject())
            ->setTitle($this->item->text)
            ->setProductType($this->item->group_text)
            ->setVendor($this->item->brand_text)
            ->setSysCol($this->item->item_color_text)
            ->setSeason($this->item->bran_number)
            ->setCompText($this->item->comp_text)
            ->setBody($this->item->storeItemText($this->store)->first()->text ?? null);

        $this->setVariants($product);

        $this->setIdentifier($product);

        return $product;
    }

    /**
     * @param ProductObject $product
     */
    protected function setVariants(ProductObject $product)
    {
        foreach ($this->item->variants as $variant) {
            $variantBuilder = new Variant($this->store, $variant);

            $product->addVariant($variantBuilder->getVariant());

        }
    }

    /**
     * @param ProductObject $product
     */
    protected function setIdentifier(ProductObject $product)
    {
        $shopifyStore = $this->item->store()->wherePivot('store_id', $this->store->id)->first();

        if ($shopifyStore) {
            $product->setId($shopifyStore->pivot->external_id);
        }
    }
}
