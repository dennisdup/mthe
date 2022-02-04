<?php

namespace App\Services\Export\DTO;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * Class DataTransferObject
 * @package App\Services\Export\DTO
 */
class DataTransferObject implements DataTransferObjectContract
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * @var string|string
     */
    protected $model;

    /**
     * DataTransferObject constructor.
     * @param array $mapping
     * @param string $model
     */
    public function __construct(array $mapping, string $model)
    {
        $this->mapping = $mapping;

        $this->setModel($model);
    }

    /**
     * @inheritDoc
     */
    public function getEntity(array $entity)
    {
        $fields = [];

        foreach ($entity as $field => $value) {
            if (array_key_exists($field, $this->mapping)) {
                $fields[$this->mapping[$field]] = !is_array($value) && $value !== '' ? $value : null;
            }
        }

        $model = $this->getModel($entity);

        $model->fill($fields);

        return $model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model)
    {
        $entity = new $model;

        if (!$entity instanceof Model) {
            $errMsg = sprintf('%s does not support %s entity', $model, Model::class);
            throw new InvalidArgumentException($errMsg);
        }

        $this->model = $model;
    }

    /**
     * @param array $entity
     * @return Model
     */
    protected function getModel(array $entity)
    {
        return new $this->model;
    }
}
