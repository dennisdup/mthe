<?php

namespace App\Services\Import\DTO;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DataTransferPbject
 * @package App\Services\Import\DTO
 */
class DataTransferObject implements DataTransferObjectContract
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * DataTransferObject constructor.
     * @param array $mapping
     */
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * @inheritDoc
     */
    public function toDto(Model $model): array
    {
        $object = [];

        foreach ($this->mapping as $property => $field) {
            $object[$field] = $model->{$property};
        }

        return $object;
    }
}
