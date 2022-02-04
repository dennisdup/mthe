<?php

namespace App\Services\Export\DTO;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface DataTransferObjectContract
 * @package App\Services\Export\DTO
 */
interface DataTransferObjectContract
{
    /**
     * @param array $entity
     * @return Model
     */
    public function getEntity(array $entity);
}
