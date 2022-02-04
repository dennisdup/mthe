<?php

namespace App\Services\Import;

use App\Models\Store;
use App\Models\Variant as Option;

/**
 * Class Variant
 * @package App\Services\Import
 */
class Variant extends AbstractImport
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

        Option::all()->each(function (Option $variant) use ($sdk, $store) {
            $object = $this->dataTransferObject->toDto($variant);

            $price = $variant->storePrice($store)->first();

            $object['option1'] = null;
            $object['presentment_prices'] = [
                [
                    'price' => [
                        'currency_code' => $price->currency,
                        'amount' => $price->price,
                    ],
                ],
            ];

            if (!$variant->shopify_id) {
                $entity = $sdk->Product($variant->item->shopify_id)->Variant->post($object);
            } else {
                $entity = $sdk->ProductVariant($variant->shopify_id)->put($object);
            }
        });
    }
}

