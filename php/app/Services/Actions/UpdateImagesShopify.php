<?php

namespace App\Services\Actions;

use App\Models\Item;
use App\Models\ProductImage;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;
use PHPShopify\ShopifySDK;

/**
 * Class UpdateImagesShopify
 *
 * @package App\Services\Actions
 */
class UpdateImagesShopify
{

    protected const IMG_SERVER_URL = 'http://167.172.98.51/';

    /**
     * @param $store
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     */
    public function update($store)
    {

        $exludeImg = [
            '.python_history',
            '194_1112_19463_02w2.jpg',
            '195_1310_19512_36w1.png',
            '202_1310_20217_40w1.jpg',
        ];

        $product_id = null;
        $sdk        = $this->getApi($store);

        foreach (Storage::disk('ftp_do')->allFiles() as $image) {

            if (in_array($image, $exludeImg)) {
                continue;
            }

            if (ProductImage::where('img_name', $image)->where('store_id', $store->id)->exists()) {
                continue;
            }

            if (!Item::where('external_id', '=', $this->getProduceSku($image))->exists()) {
                file_put_contents('WrongSku.txt', $this->getProduceSku($image) . "\n", FILE_APPEND);
                continue;
            }

            Item::where('external_id', '=', $this->getProduceSku($image))
                ->with(['store' => function ($q) use ($store) {
                    $q->where('id', $store->id);
                }])
                ->first()
                ->store
                ->map(function ($store) use (&$product_id) {
                    $product_id = $store->pivot->external_id;
                });

            $params = [
                'src'      => self::IMG_SERVER_URL . $image,
                'position' => $this->getPosition($image),
            ];

            if ($sdk->Product($product_id)->get() !== null) {
                try {
                    $shopifyImage = $sdk->Product($product_id)->Image->post($params);
                    $this->saveNewImage($image, $product_id, $store->id, $shopifyImage);
                    sleep(3);
                } catch (\Exception $exception) {
                    file_put_contents('wrong_img.txt', $exception->getMessage(), FILE_APPEND);
                    continue;
                }
            }

        }

    }

    /**
     * @param $item
     *
     * @return mixed|string
     */
    protected function getProduceSku($item)
    {
        $removeSpace  = substr_replace($item, '', 8, 1);
        $replace2zero = substr_replace($removeSpace, '0', 3, 1);
        $replace2     = substr_replace($replace2zero, '0000', 13, 1);
        $sku          = explode('w', $replace2)[0];
        return str_replace('_', '', $sku);
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
     * @param Store $store
     *
     * @return mixed|string
     */
    protected function getShopName(Store $store)
    {
        $step1 = explode('@', $store->shop_name);
        $step2 = explode('.', $step1[1]);
        return $step2[0];
    }

    /**
     * @param $image
     *
     * @return mixed
     */
    protected function checkImageUpdateTime($image, $store_id)
    {
        return ProductImage::select('last_modified')
                           ->where('img_name', $image)
                           ->where('store_id', $store_id)
                           ->first()->last_modified ?? null;
    }

    /**
     * @param $image
     *
     * @return string|string[]
     */
    protected function getPosition($image)
    {
        $position = substr($image, -5, 2);

        if ($position == 'w.') {
            return 1;
        } else {
            $resultposition = str_replace('.', '', $position);
            return (int)$resultposition + 1;
        }
    }

    /**
     * @param      $image
     * @param null $product_id
     *
     * @param      $shop_id
     * @param      $shopify_image_id
     *
     * @return string
     */
    protected function saveNewImage($image, $product_id, $shop_id, $shopify_image_id)
    {
        if (ProductImage::where('img_name', $image)->where('store_id', $shop_id)->exists()) {
            $product = ProductImage::where('img_name', $image)->where('store_id', $shop_id)->first();
        } else {
            $product = new ProductImage();
        }

        $product->img_name         = $image;
        $product->position         = $this->getPosition($image);
        $product->last_modified    = Storage::disk('ftp_do')->lastModified($image);
        $product->product_id       = $product_id;
        $product->store_id         = $shop_id;
        $product->shopify_image_id = $shopify_image_id['id'];
        $product->save();
    }

    /**
     * @param $sdk
     * @param $product_id
     *
     * @return mixed
     */
    protected function clearProductImg($sdk, $product_id)
    {
        $excludeProduct = [
            '4860143730820',
            '4534946955396',
            '4861859823748',
        ];

        if (in_array($product_id, $excludeProduct)) {
            return false;
        }

        $product = $sdk->Product($product_id)->get();

        if (empty($product['images'])) {
            return false;
        }

        foreach ($product['images'] as $item) {
            $sdk->Product($product_id)->Image($item['id'])->delete();
        }
    }
}
