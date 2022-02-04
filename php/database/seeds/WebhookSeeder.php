<?php

use App\Models\Store;
use Illuminate\Database\Seeder;
use PHPShopify\ShopifySDK;

/**
 * Class WebhookSeeder
 */
class WebhookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = \App\Models\Store::where('id', '!=', '1')->get();

        /** @var Store $store */
        foreach ($stores as $store) {
            $sdk  = $this->getApi($store);

            $response = $sdk->Webhook->post([
                'topic' => 'orders/create',
                'address' => route('orders.create', ['storeId' => $store->id]),
                'format' => 'json',
            ]);

            dd($response);
//
//            $sdk->Webhook->post([
//                'topic' => 'orders/delete',
//                'address' => route('orders.delete'),
//                'format' => 'json',
//            ]);
//
//            $sdk->Webhook->post([
//                'topic' => 'orders/cancelled',
//                'address' => route('orders.cancelled'),
//                'format' => 'json',
//            ]);
        }
    }

    /**
     * @param Store $store
     * @return ShopifySDK
     */
    protected function getApi(Store $store)
    {
        return new ShopifySDK([
            'ApiUrl' => $store->shop_name,
            'ApiKey' => $store->api_key,
            'Password' => $store->api_password,
        ]);
    }
}
