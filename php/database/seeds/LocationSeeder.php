<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Store::all()->each(function (\App\Models\Store $store) {
            $sdk = $this->getApi($store);

            $locations = collect($sdk->Location->get());

            dump($locations);

            $store->location_id = $locations->first() ? $locations->first()['id'] : null;

            $store->save();
        });
    }

    /**
     * @param \App\Models\Store $store
     * @return \PHPShopify\ShopifySDK
     */
    protected function getApi(\App\Models\Store $store)
    {
        return new \PHPShopify\ShopifySDK([
            'ApiUrl' => $store->shop_name,
            'ApiKey' => $store->api_key,
            'Password' => $store->api_password,
        ]);
    }
}
