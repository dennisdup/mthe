<?php

namespace App\Services\Import\DTO;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface DataTransferObjectContract
 * @package App\Services\Import\DTO
 */
interface DataTransferObjectContract
{
    /**
     * @param Model $model
     * @return array
     */
    public function toDto(Model $model): array;
}
