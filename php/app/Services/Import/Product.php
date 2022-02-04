<?php

namespace App\Services\Import;

use App\Models\Item;
use App\Models\Store;
use App\Models\Variant as Option;
use PHPShopify\ShopifySDK;

/**
 * Class Product
 * @package App\Services\Import
 */
class Product extends AbstractImport
{
    /**
     * @param \App\Models\Store $store
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     */
    public function import(Store $store)
    {
        $sdk = $this->getApi($store);

        Item::all()->each(function (Item $item) use ($sdk, $store) {
            $object = $this->dataTransferObject->toDto($item);
            $object['variants'] = $this->getVariants($item, $store);

            if ($item->shopify_id) {
                $product = $sdk->Product($item->shopify_id);
                $product->put($object);

                dump($product->get());
            } else {
                dump($object);
                $product = $sdk->Product->post($object);

                $item->shopify_id = $product['id'];
                $item->save();

                $this->saveVariants($item, $product['variants']);
            }
        });
    }

    /**
     * @param Item $item
     * @param Store $store
     * @return mixed
     */
    protected  function getVariants(Item $item, Store $store)
    {
        return $item->variants->map(function (Option $variant) use ($store) {
            $price = $variant->storePrice($store)->first();

            $object = [
                'option1' => $variant->colour_text ?? $variant->item_text,
                'price' => $price->price,
                'presentment_prices' => [
                    [
                        'price' => [
                            'currency_code' => $price->currency,
                            'amount' => $price->price,
                        ]
                    ]
                ]
            ];

            if ($variant->shopify_id) {
                $object['id'] = $variant->shopify_id;
            }

            return $object;
        })->toArray();
    }

    /**
     * @param Item $item
     * @param array $variants
     */
    protected function saveVariants(Item $item, array $variants)
    {
        foreach ($item->variants as $index => $variant) {
            $variant->shopify_id = $variants[$index]['id'];
            $variant->save();
        }
    }


}

