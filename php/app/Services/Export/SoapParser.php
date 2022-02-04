<?php

namespace App\Services\Export;

/**
 * Class SoapParser
 *
 * @package App\Services\Export
 */
class SoapParser extends AbstractSoapExporter implements ParserContract
{
    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $collection = $this->getCollection();

        foreach ($collection as $k => $item) {
            $entity = $this->dataTransferObject->getEntity($item);

            if ($entity) {
                $entity->save();
            }
        }
    }
}
