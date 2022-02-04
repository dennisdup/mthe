<?php

namespace App\Services\Actions;

use App\Events\OrderCreated;
use App\Models\Store;
use App\Models\Variant;
use App\Services\Actions\Traits\CreateOrderTrait;
use App\Services\OrderService;
use Carbon\Carbon;
use PHPShopify\ShopifySDK;

/**
 * Class CreateOrder
 *
 * @package App\Services\Actions
 */
class CreateOrder
{
    use CreateOrderTrait;

    /**
     * @var string
     */
    protected $wsdl;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;
    /**
     * @var
     */
    protected $orderService;

    protected const DEFAULT_PROJECT_NAME = 'MUNTHE';
    protected const DEFAULT_COUNTRY_CODE = 'DK';
    protected const EN_COUNTRY_CODE      = 'EN';

    /**
     * CreateOrder constructor.
     *
     * @param string $wsdl
     * @param string $user
     * @param string $password
     */
    public function __construct(
        string $wsdl,
        string $user,
        string $password
    )
    {
        $this->wsdl     = $wsdl;
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * @param \App\Models\Store          $store
     * @param array                      $orderData
     * @param \App\Services\OrderService $orderService
     *
     * @return false
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @throws \SoapFault
     */
    public function createOrder(Store $store, array $orderData, OrderService $orderService)
    {
        if ($this->checkOrder($orderData['id'])) {
            return false;
        }

        $client = new \SoapClient(base_path() . "/EA6183RA.wsdl.xml", [
            'location' => $this->wsdl,
            'trace'    => true,
        ]);

        $response = $client->createOrder(
            [
                'credentials' => [
                    'user'     => $this->user,
                    'password' => $this->password,
                ],
                'request'     => [
                    'element'         => 'createorder',
                    'etiktxt1'        => $this->getPaymentInformation($orderData)['order_id'] ?? null,
                    'sessionid'       => $orderData['email'],
                    'currency'        => $orderData['currency'],
                    'countrycode'     => $this->getCountryCode($orderData),
                    'weborder'        => $this->makeWebOrderId($orderData),
                    'mail'            => $orderData['email'],
                    'name'            => $orderData['shipping_address']['name'] ?? null,
                    'street'          => $orderData['shipping_address']['address1'] ?? null,
                    'zip'             => $orderData['shipping_address']['zip'] ?? null,
                    'town'            => $orderData['shipping_address']['city'] ?? null,
                    'country'         => $this->getCountryCode($orderData) ?? null,
                    'phonenumber'     => $orderData['shipping_address']['phone'] ?? null,
                    'date'            => $this->makeDate($orderData),
                    'delivery'        => $orderService->getDeliveryName($orderData),
                    'dibsid'          => $this->getPaymentInformation($orderData)['id'] ?? null,
                    'freight'         => $this->shipingPrice($orderData) ?? 0,
                    'remark'          => $orderData['note'],
                    'deliveryname'    => $this->setDeliveryTitle($orderData),
                    'deliverystreet'  => $orderService->getDeliveryStreet($orderData),
                    'deliveryzip'     => $orderService->getDeliveryZip($orderData),
                    'deliverytown'    => $orderService->getDeliveryCity($orderData),
                    'deliverycountry' => $orderService->getDeliveryCountry($orderData),
                    'deliveryatt'     => $orderData['shipping_address']['name'] ?? null,
                    'packageshopid'   => $this->parsePackageShopid($orderData['shipping_lines']),
                    'shopid'          => $this->getShopId($orderData),
                    'paymenttype'     => $this->getPaymentInformation($orderData)['acquirer'] ?? null,
                    'cardtype'        => $this->getPaymentInformation($orderData)['metadata']['brand'] ?? null,
                    'grplines'        => array_map(function (array $item) use ($store) {
                        return $this->makeProductData($item, $store);
                    }, $orderData['line_items']),
                ],
            ]);

        $store->order()->create([
            'web_id'               => $orderData['id'],
            'shopify_order_number' => $orderData['name'],
        ]);

        $this->subItems($orderData);
    }

    /**
     * @param array $order
     */
    protected function subItems(array $order)
    {
        $items = array_map(function (array $line) {

            $variantId = $line['sku'];

            $variant = Variant::where('external_id', $variantId)->first();

            if ($variant !== null) {
                $variant->stock->quantity -= $line['quantity'];
                $variant->stock->save();

                return $variant->item;
            }
            return null;

        }, $order['line_items']);

        $items = array_filter($items);

        event(new OrderCreated($items));
    }

    /**
     * @param                   $orderProduct
     * @param \App\Models\Store $store
     *
     * @return array
     */
    public function makeProductData($orderProduct, Store $store): array
    {
        $sdk = new ShopifySDK([
            'ApiUrl'   => $store->shop_name,
            'ApiKey'   => $store->api_key,
            'Password' => $store->api_password,
        ]);

        try {
            $product = $sdk->ProductVariant($orderProduct['variant_id'])->get();

            return [
                'variantid'     => $product['sku'] ?? null,
                'quantity'      => $orderProduct['quantity'],
                'price'         => $this->countProductPrice($orderProduct),
                'itemno'        => "",
                'ean'           => '',
                'field4'        => '',
                'size'          => $product['option1'],
                'itemdiscount'  => '',
                'text'          => '',
                'deliveryweek'  => '',
                'sizetext'      => '',
                'deliverydatel' => '',
                'time1'         => Carbon::now()->getTimestamp(),
                'sku'           => $product['sku'] ?? null,
                'barcode'       => $product['barcode'] ?? null,
            ];

        } catch (\Exception $exception) {
            $text = [
                'id'   => $orderProduct['variant_id'],
                'text' => $exception->getMessage(),
            ];

            file_put_contents('error_order_update_shopify.log', print_r($text, true), FILE_APPEND);
            return [];
        }
    }

    /**
     * @param $orderProduct
     *
     * @return mixed
     */
    protected function countProductPrice($orderProduct)
    {
        if (empty($orderProduct['discount_allocations'])) {
            return $orderProduct['price'];
        }

        return $orderProduct['price'] - $orderProduct['discount_allocations'][0]['amount'];
    }
}
