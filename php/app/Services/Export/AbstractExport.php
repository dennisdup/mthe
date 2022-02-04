<?php

namespace App\Services\Export;

use App\Services\Export\DTO\DataTransferObjectContract;
use Illuminate\Support\Facades\Storage;

/**
 * Class AbstractExport
 * @package App\Services\Export
 */
abstract class AbstractExport
{
    /**
     * @var string|string
     */
    protected $disk;

    /**
     * @var string|string
     */
    protected $filepath;

    /**
     * @var DataTransferObjectContract
     */
    protected $dataTransferObject;

    /**
     * ItemExport constructor.
     * @param DataTransferObjectContract $dataTransferObject
     * @param string $disk
     * @param string $filepath
     */
    public function __construct(
        DataTransferObjectContract $dataTransferObject,
        string $disk,
        string $filepath
    ) {
        $this->disk = $disk;
        $this->filepath = $filepath;
        $this->dataTransferObject = $dataTransferObject;
    }

    /**
     * @return void
     */
    public abstract function parse();

    /**
     * @return DataTransferObjectContract
     */
    protected function getDataTransferObject()
    {
        return $this->dataTransferObject;
    }

    /**
     * @return iterable
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getCollection()
    {
        $fileContent = Storage::disk($this->disk)->get($this->filepath);

        $xml = simplexml_load_string($fileContent);

        $json = json_encode($xml);

        $collection = json_decode($json, true);

        return $collection[array_keys($collection)[0]];
    }

}
