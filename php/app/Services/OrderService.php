<?php

namespace App\Services;

/**
 * Class OrderService
 *
 * @package App\Services
 */
class OrderService
{
    /**
     * @param $orderData
     *
     * @return mixed
     */
    public function getDeliveryStreet($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {
                if (strpos($orderDatum['code'], 'GLSSD') !== false) {

                    if (strpos($orderDatum['title'], '7-Eleven') !== false) {
                        $orderDatum['title'] = str_replace('7-Eleven', '', $orderDatum['title']);
                    }

                    preg_match("/(?<=-)(.*)(?=,)/", $orderDatum['title'], $string);
                    return $string[0];
                }

                return $orderData['customer']['default_address']['address1'];

            }
        }
    }

    /**
     * @param $orderData
     *
     * @return mixed|null
     */
    public function getDeliveryCountry($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {
                if (strpos($orderDatum['code'], 'GLSSD') !== false) {
                    return $orderData['shipping_address']['country_code'];
                }
                return $orderData['customer']['default_address']['country_code'] ?? null;
            }
        }

    }

    /**
     * @param $orderData
     *
     * @return mixed
     */
    public function getDeliveryZip($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {
                if (strpos($orderDatum['code'], 'GLSSD') !== false) {
                    return $orderData['shipping_address']['zip'] ?? null;
                }

                return $orderData['customer']['default_address']['zip'];

            }
        }
    }

    /**
     * @param $orderData
     *
     * @return mixed
     */
    public function getDeliveryCity($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {
                if (strpos($orderDatum['code'], 'GLSSD') !== false) {
                    return $orderData['shipping_address']['city'];
                }

                return $orderData['customer']['default_address']['city'] ?? null;

            }
        }
    }

    /**
     * @param $orderData
     *
     * @return mixed|string
     */
    public function getDeliveryName($orderData)
    {
        if (!empty($orderData)) {
            foreach ($orderData['shipping_lines'] as $orderDatum) {
                if (strpos($orderDatum['code'], 'GLSSD') !== false) {
                    return 'GLSSD';
                }

                return $orderDatum['title'];
            }
        }
    }
}
