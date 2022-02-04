<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\Variant;

/**
 * Class UpdateStockService
 *
 * @package App\Services
 */
class UpdateStockService
{
    /**
     * @return mixed
     * @throws \SoapFault
     */
    public function getStockFromErp()
    {
        $variants = Variant::select('external_id')->get()->map(function ($variant) {
            return $variant->external_id;
        });

        $client = new \SoapClient(base_path() . "/EA6183RA.wsdl.xml", [
            'location' => 'https://texb2b.aspect4.com/muv_webservices/services/EA6183RA.EA6183RA',
            'trace'    => true,
        ]);

        $response = $client->getstock([
            'credentials' => [
                'user'     => 'MUVWEB',
                'password' => 'muv756ab',
            ],
            'request'     => [
                'variantid' => $variants->toArray(),
            ],
        ]);

        return $this->updateStocks(json_decode(json_encode($response), true));
    }

    /**
     * @param $stocks
     */
    protected function updateStocks($stocks)
    {
        foreach ($stocks['response']['grpstock'] as $k => $stock) {

            $variant = Variant::select('id')->where('external_id', (string)$stock['variantid'])->first();

            $stockDB             = Stock::firstOrNew(['variant_id' => $variant->id]);
            $stockDB->quantity   = $stock['quantity'];
            $stockDB->variant_id = $variant->id;

            $stockDB->save();

        }
    }
}
