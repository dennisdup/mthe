<?php

namespace App\Services\Importer;

use App\Models\Item;
use App\Models\Store;
use App\Models\Variant;
use App\Services\Importer\Builders\Product;
use App\Services\Importer\Denormalizers\Product as ProductDenormalizer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use PHPShopify\ShopifySDK;

/**
 * Class Importer
 *
 * @package Symfony\Contracts\Service\Importer
 */
class Importer
{
    /**
     * @param Store|null $store
     *
     * @return void
     */
    public function run(?Store $store): void
    {
        if ($store) {
            $this->chunkItems($store);

            return;
        }

        Store::each(function (Store $store) {
            $this->chunkItems($store);
        });
    }

    /**
     * @param Store $store
     *
     * @return void
     */
    protected function chunkItems(Store $store): void
    {
        Item::has('variants.price')->chunk(200, function (Collection $itemsCollection) use ($store) {
            $this->items($store, $itemsCollection);
        });
    }

    /**
     * @param Store      $store
     * @param Collection $itemsCollection
     *
     * @return void
     */
    protected function items(Store $store, Collection $itemsCollection): void
    {
        $itemsCollection->each(function (Item $item) use ($store) {
            $this->item($store, $item);
        });
    }

    /**
     * @param Store $store
     * @param Item  $item
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     */
    public function item(Store $store, Item $item): void
    {
        $productBuilder = new Product($store, $item);

        $product = $productBuilder->getProduct();

        $productDenormalizer = new ProductDenormalizer($product);

        $denormalizedProduct = $productDenormalizer->getProduct();
        $variants            = $productDenormalizer->stockUpdate();

        if (!$product->getId()) {

            $this->create($store, $item, $denormalizedProduct);

            $logtext = [
                'store'   => $store->id,
                'product' => $denormalizedProduct,
                'date'    => Carbon::now()->getTimestamp(),
                'method'  => 'Create',
            ];

            file_put_contents('create_new_product.log', print_r($logtext, true), FILE_APPEND);
        } else {
            $this->updateVariant($product->getId(), $variants, $store);
        }

    }

    /**
     * @param \App\Models\Store $store
     * @param \App\Models\Item  $item
     * @param array             $denormalizedProduct
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     */
    protected function create(Store $store, Item $item, array $denormalizedProduct): void
    {
        $sdk = $this->getApi($store);

        $product = $sdk->Product->post($denormalizedProduct);

        $store->items()->attach([$item->id => ['external_id' => $product['id']]]);

        $item->save();

        /**
         * @var int     $index
         * @var Variant $variant
         */
        foreach ($item->variants as $index => $variant) {

            $inventoryItem = $sdk->InventoryLevel->set([
                'inventory_item_id' => $product['variants'][$index]['inventory_item_id'],
                'location_id'       => $store->location_id,
                'available'         => 1,
            ]);

            $store->variants()->attach([
                $variant->id => [
                    'external_id'       => $product['variants'][$index]['id'],
                    'inventory_item_id' => $product['variants'][$index]['inventory_item_id'] ?? '',
                ],
            ]);

            $variant->save();
        }
    }

    /**
     * @param Store $store
     *
     * @return ShopifySDK
     */
    protected function getApi(Store $store)
    {
        return new ShopifySDK([
            'ApiUrl'   => $store->shop_name,
            'ApiKey'   => $store->api_key,
            'Password' => $store->api_password,
        ]);
    }

    /**
     * @param $id
     * @param $product
     * @param $store
     *
     */
    protected function updateVariant($id, $product, $store)
    {
        try {
            foreach ($product as $item) {

                $shopifyVariant = $this->getApi($store)->ProductVariant($item['id'])->get();

                if (empty($item['inventory_item_id'])) {
                    DB::table('shopifies')
                      ->where('external_id', $item['id'])
                      ->update(['inventory_item_id' => $shopifyVariant['inventory_item_id']]);
                }

                $this->getApi($store)->InventoryLevel->set([
                    'available'         => $item['inventory_quantity'],
                    "inventory_item_id" => $shopifyVariant['inventory_item_id'],
                    'location_id'       => $store->location_id,
                ]);
            }
        } catch (\Exception $exception) {
            $text = [
                'id'      => $id,
                'message' => $exception->getMessage(),
                'shopId'  => $store->id,
            ];
            file_put_contents('error_stock_update_shopify.log', print_r($text, true));
        }
    }

    /**
     * @param \App\Models\Store $store
     *
     * @return mixed|string
     */
    protected function getShopName(Store $store)
    {
        $step1 = explode('@', $store->shop_name);
        $step2 = explode('.', $step1[1]);
        return $step2[0];
    }
}
