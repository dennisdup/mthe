<?php

namespace App\Services\Export;

/**
 * Class Parser
 * @package App\Services\Export
 */
class Parser extends AbstractExport
{
    /**
     * @inheritDoc
     */
    public function parse()
    {
        $collection = $this->getCollection();

        foreach ($collection as $item) {

            $entity = $this->getDataTransferObject()->getEntity($item);

            if ($entity) {
                $entity->save();
            }
        }
    }
}
