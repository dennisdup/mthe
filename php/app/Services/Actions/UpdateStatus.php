<?php

namespace App\Services\Actions;

use App\Models\Order;
use App\Models\Store;
use GuzzleHttp\Client;
use PHPShopify\ShopifySDK;

/**
 * Class UpdateStatus
 *
 * @package App\Services\Actions
 */
class UpdateStatus
{
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
     * UpdateStatus constructor.
     *
     * @param string $wsdl
     * @param string $user
     * @param string $password
     */
    public function __construct(string $wsdl, string $user, string $password)
    {
        $this->wsdl     = $wsdl;
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * @throws \SoapFault
     */
    public function updateByOrder()
    {

        $client = new \SoapClient($this->wsdl, [
            'location' => $this->wsdl,
        ]);

        $response = $client->getdeledeli([
            'credentials' => [
                'user'     => $this->user,
                'password' => $this->password,
            ],
        ]);

        $erpOrders = json_decode(json_encode($response), true);
        if (count($erpOrders['response']['recordbreak']) > 0) {

            foreach ($erpOrders['response']['recordbreak'] as $k => $erpOrder) {

                $order = Order::where(['shopify_order_number' => $this->getWebId($erpOrder)])
                              ->where('is_active', true)
                              ->first();

                if (!$order) {
                    continue;
                }

                if ($erpOrder['t01Aspect4Invoice'] != 0) {
                    $this->updateFulFieldStatus($order, $erpOrder);
                    $order->is_active = false;;
                }

                if ($erpOrder['t01Deleteddate'] != 0) {
                    $api = $this->getApi($order->store);
                    $api->Order($order->web_id)->cancel([]);
                    $order->is_active = false;
                }

                $order->save();
            }
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
     * @param array $order
     *
     * @return int
     */
    protected function getWebId(array $order)
    {
        if (!empty($order['t01Weborder'])) {
            return $order['t01Weborder'];
        }

        if (!empty($order['t01Webordre'])) {
            return $order['t01Webordre'];
        }
    }

    /**
     * @param $order
     * @param $erpOrder
     */
    protected function updateFulFieldStatus($order, $erpOrder)
    {
        try {

            $client = new Client();
            $client->post(
                'https://' .
                $order->store->api_key . ':' .
                $order->store->api_password . '@' .
                $this->getShopName($order->store) . '.myshopify.com/admin/orders/' .
                $order->web_id . '/fulfillments.json',
                [
                    'form_params' => [
                        "fulfillment" => [
                            'fulfillment_status' => 'fulfilled',
                            'tracking_url'       => $erpOrder['url'],
                            'location_id'        => $order->store->location_id,
                        ],
                    ],
                ]
            );
        } catch (\Exception $exception) {
            $erpOrder['message'] = $exception->getMessage();
            file_put_contents('error_order_update_status.log', print_r($erpOrder, true), FILE_APPEND);
        }
    }

    /**
     * @param \App\Models\Store $store
     *
     * @return mixed
     */
    protected function getShopName(Store $store)
    {
        $step1 = explode('@', $store->shop_name);
        $step2 = explode('.', $step1[1]);
        return $step2[0];
    }
}
