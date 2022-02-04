<?php

namespace App\Services\Export;

use App\Models\Stock;
use App\Models\Variant;
use App\Services\LogService;
use Facades\App\Services\Actions\GetStock;

/**
 * Class StockSoapParser
 *
 * @package App\Services\Export
 */
class StockSoapParser extends SoapParser
{
    protected const LOG_FILE_NAME = 'StockSoapParserLog.txt';

    /**
     * @return array
     */
    public function getCollection(): array
    {
        $variants = Variant::where('external_id', 'like', '202%')->orderBy('id', 'desc')->get();

        $t = 0;
        foreach ($variants as $variant) {
            $t++;

            if (!Stock::where('variant_id', $variant['id'])->exists()) {
                $this->updateStocks($variant);
            } else {
                Stock::select('updated_at')->where('variant_id', $variant['id'])->first();

                $this->updateStocks($variant);
            }
        }

        return [];
    }

    protected function updateStocks($variant)
    {
        try {
            $model = GetStock::getByVariantid($variant->external_id);

            if ($model) {
                $model->save();
                echo "\n Success Stock - " . $variant->external_id . " \n";
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            LogService::saveToLog(self::LOG_FILE_NAME, $exception, $variant['id']);
        }
    }
}
