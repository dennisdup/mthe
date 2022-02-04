<?php

namespace App\Services\Import;

use App\Models\Store;
use App\Services\Import\DTO\DataTransferObjectContract;
use PHPShopify\ShopifySDK;

/**
 * Class AbstractImport
 * @package App\Services\Import
 */
abstract class AbstractImport
{
    /**
     * @var DataTransferObjectContract
     */
    protected $dataTransferObject;

    /**
     * Product constructor.
     * @param DataTransferObjectContract $dataTransferObject
     */
    public function __construct(DataTransferObjectContract $dataTransferObject)
    {
        $this->dataTransferObject = $dataTransferObject;
    }

    /**
     * @param Store $store
     * @return void
     */
    abstract function import(Store $store);

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
