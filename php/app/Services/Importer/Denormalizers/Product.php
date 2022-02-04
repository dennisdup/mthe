<?php

namespace App\Services\Importer\Denormalizers;

use App\Services\Importer\Objects\Option;
use App\Services\Importer\Objects\Product as ProductObject;
use App\Services\Importer\Objects\Variant;

/**
 * Class Product
 *
 * @package App\Services\Importer\Denormalizers
 */
class Product
{
    /**
     * @var ProductObject
     */
    protected $product;

    /**
     * Product constructor.
     *
     * @param ProductObject $product
     */
    public function __construct(ProductObject $product)
    {
        $this->product = $product;
    }

    /**
     * @return array
     */
    public function getProduct(): array
    {
        $product = [
            'title'        => $this->product->getTitle(),
            'body_html'    => $this->product->getBody(),
            'vendor'       => $this->product->getVendor(),
            'product_type' => $this->product->getProductType(),
            'variants'     => $this->getVariants(),
            "tags"         =>
                "SYSCOL - " . $this->product->getSysCol() . "," .
                "SEASON - " . $this->product->getSeason() . "," .
                "COMP - " . $this->product->getCompText() . ","
        ];

        if (!$this->product->getId()) {
            $product['published'] = false;
        }

        return $product;
    }

    /**
     * @return array
     */
    public function getVariants(): array
    {
        return $this->product->getVariants()->map(function (Variant $variant) {
            $object = [
                'inventory_quantity'   => 0,
                'inventory_management' => 'shopify',
                'sku'                  => $variant->getSku(),
                'barcode'              => $variant->getEan(),
                'price'                => $variant->getPrice(),
            ];

            if ($variant->getId()) {
                $object['id'] = $variant->getId();
            }

            /**
             * @var int    $index
             * @var Option $option
             */
            foreach ($variant->getOptions()->values() as $index => $option) {
                $object['option' . ($index + 1)] = $option->getValue();
            }

            return $object;
        })->toArray();
    }

    /**
     * @return array
     */
    public function stockUpdate()
    {
        return $this->product->getVariants()->map(function (Variant $variant) {

            $object = [
                'inventory_quantity' => (int)$variant->getCount(),
                'inventory_item_id'  => $variant->getInventoryLevel(),
            ];

            if ($variant->getId()) {
                $object['id'] = $variant->getId();
            }

            return $object;
        })->toArray();
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        $options = $this->product->getVariants()->map(function (Variant $variant) {
            return $variant->getOptions()->values();
        })->collapse();

        return $options->map(function (Option $option) {
            return [
                'name' => $option->getName(),
            ];
        })->toArray();
    }
}
