<?php

namespace App\Services\Import;

use App\Models\Store;

/**
 * Class Importer
 *
 * @package App\Services\Import
 */
class Importer
{
    /**
     * @var AbstractImport[]
     */
    protected $imports;

    /**
     * Importer constructor.
     * @param AbstractImport[] $imports
     */
    public function __construct(iterable $imports)
    {
        $this->imports = $imports;
    }

    /**
     * @return void
     */
    public function run()
    {
        Store::where('shop_name', 'like', '%munthedev%')->get()->each(function (Store $store) {
            foreach ($this->imports as $import) {
                $import->import($store);
            }
        });
    }
}
