<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use App\Models\Store;

/**
 * Class StoreSeeder
 */
class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->dataProvider()->each(function (array $credentials) {
            $store = new Store();
            $store->fill($credentials);
            $store->save();
        });
    }

    /**
     * @return Collection
     */
    protected function dataProvider(): Collection
    {
        return collect([
            [
                'api_key' => '833b48df9d7c671bdd2d7e6a5aa03d88',
                'shop_name' => 'https://833b48df9d7c671bdd2d7e6a5aa03d88:c885eafe32b95f42ecb37e9a39944b57@munthedev.myshopify.com/admin/api/2020-01/',
                'api_password' => 'c885eafe32b95f42ecb37e9a39944b57',
                'api_secret' => 'bd69fb58417a9fe15622645353f5effd',
                'language' => 'dk',
                'currency' => 'DKK',
            ],
            [
                'api_key' => 'ccf5c12ccfeb4b3c195398cc8987bed2',
                'shop_name' => 'https://ccf5c12ccfeb4b3c195398cc8987bed2:a2635d5a11297c70afe5983d949e9cf1@sweden-munthe.myshopify.com/admin/api/2020-01/',
                'api_password' => 'a2635d5a11297c70afe5983d949e9cf1',
                'api_secret' => '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',
                'language' => 'en',
                'currency' => 'SEK',
            ],
            [
                'api_key' => '6a390bef9e68aca45a6ba04a492a5c85',
                'shop_name' => 'https://6a390bef9e68aca45a6ba04a492a5c85:d222b0b0af6aa51ba69be82e1dc7cb99@europe-munthe.myshopify.com/admin/api/2020-01/',
                'api_password' => 'd222b0b0af6aa51ba69be82e1dc7cb99',
                'api_secret' => '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',
                'language' => 'en',
                'currency' => 'EUR',
            ],
            [
                'api_key' => 'cd526b3bf3311e935fc4cd09caf60f78',
                'shop_name' => 'https://cd526b3bf3311e935fc4cd09caf60f78:4c25ebe2e01e9e1d7828b5d437c6610b@norway-munthe.myshopify.com/admin/api/2020-01/',
                'api_password' => '4c25ebe2e01e9e1d7828b5d437c6610b',
                'api_secret' => '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',
                'language' => 'en',
                'currency' => 'NOK',
            ],
            [
                'api_key' => '4b660bd573e75cdfd2d130d92a3a9637',
                'shop_name' => 'https://4b660bd573e75cdfd2d130d92a3a9637:91487416afe31ce727cafeecabaed544@world-munthe.myshopify.com/admin/api/2020-01/',
                'api_password' => '91487416afe31ce727cafeecabaed544',
                'api_secret' => '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',
                'language' => 'en',
                'currency' => 'EUR',
            ],
        ]);
    }
}
