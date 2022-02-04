<?php

namespace App\Services\Actions\Traits;

use App\Models\Order;
use Carbon\Carbon;
use QuickPay\QuickPay;

/**
 * Trait CreateOrderTrait
 *
 * @package App\Services\Actions\Traits
 */
trait CreateOrderTrait
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public function parsePackageShopid($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData as $orderDatum) {
                if (strpos($orderDatum['code'], 'mycommerce360') !== false) {
                    return explode('/', $orderDatum['code'])[0];
                }
            }
        }
        return null;
    }

    /**
     * @param $orderData
     *
     * @return mixed|string
     */
    public function setDeliveryTitle($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {

                if (strpos($orderDatum['code'], 'mycommerce360') !== false) {
                    if (strpos($orderDatum['title'], '7-Eleven') !== false) {
                        return explode(' ', $orderDatum['title'])[0];
                    }
                    return explode('-', $orderDatum['title'])[0];
                }
            }
            return $orderData['customer']['default_address']['name'];
        }
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function checkOrder(int $id): bool
    {
        return Order::where('web_id', $id)->exists();
    }

    /**
     * @param array $orderData
     *
     * @return int|string
     */
    public function makeDate(array $orderData)
    {
        $date = Carbon::parse($orderData['created_at'])->year;

        if (Carbon::parse($orderData['created_at'])->month < 10) {
            $date .= '0' . Carbon::parse($orderData['created_at'])->month;
        } else {
            $date .= Carbon::parse($orderData['created_at'])->month;
        }

        if (Carbon::parse($orderData['created_at'])->day < 10) {
            $date .= '0' . Carbon::parse($orderData['created_at'])->day;
        } else {
            $date .= Carbon::parse($orderData['created_at'])->day;

        }

        return $date;
    }

    /**
     * @param array $orderData
     *
     * @return string
     */
    public function makeWebOrderId(array $orderData)
    {
        $string = str_replace('#', '', $orderData['name']) . "_" . $orderData['id'];
        $strArr = explode('_', $string);
        if (isset($strArr[1])) {
            return $strArr[0];
        }
    }

    /**
     * @param array $orderData
     *
     * @return string
     */
    public function getShopId(array $orderData)
    {
        $host = parse_url($orderData['order_status_url'])['host'];

        $domains = [
            'europe-munthe.myshopify.com',
            'world-munthe.myshopify.com',
            'www.en.munthe.com',
        ];

        if (in_array($host, $domains)) {
            return self::DEFAULT_PROJECT_NAME . "-" . self::EN_COUNTRY_CODE;
        }

        return self::DEFAULT_PROJECT_NAME . "-DK";
    }

    /**
     * @param array $orderData
     *
     * @return string|string[]
     */
    public function getCountryCode(array $orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {
                if (strpos($orderDatum['code'], 'GLSSD') !== false) {
                    return $orderData['shipping_address']['country_code'];
                }
                return $orderData['customer']['default_address']['country_code'] ?? null;
            }
        }

        return self::DEFAULT_COUNTRY_CODE;
    }

    /**
     * @param $orderProduct
     *
     * @return array
     */
    public function getPaymentInformation($orderProduct)
    {
        $apikey = env('QUIQPAY_API');
        $quick  = new QuickPay(":{$apikey}");

        $form = [
            'order_id' => $orderProduct['checkout_id'],
        ];

        $responce = $quick->request->get('/payments/', $form)->asArray();

        if (!empty($responce)) {
            return $responce[0];
        }

        return null;
    }

    /**
     * @param $orderData
     *
     * @return mixed
     */
    public function shipingPrice($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {
                return $orderDatum['price'];;
            }
        }
    }
}
