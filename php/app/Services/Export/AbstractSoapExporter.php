<?php

namespace App\Services\Export;

use App\Services\Export\DTO\DataTransferObject;

/**
 * Class AbstractSoapExporter
 *
 * @package App\Services\Export
 */
abstract class AbstractSoapExporter
{
    /**
     * @var DataTransferObject
     */
    protected $dataTransferObject;

    /**
     * @var string
     */
    protected $wsdl;

    /**
     * @var string
     */
    protected $function;

    /**
     * AbstractSoapExporter constructor.
     *
     * @param DataTransferObject $dataTransferObject
     * @param string             $wsdl
     * @param string             $function
     */
    public function __construct(
        DataTransferObject $dataTransferObject,
        string $wsdl,
        string $function
    )
    {
        $this->dataTransferObject = $dataTransferObject;
        $this->wsdl               = $wsdl;
        $this->function           = $function;
    }

    /***
     * @return array
     * @throws \SoapFault
     */
    public function getCollection(): array
    {
        $client = new \SoapClient($this->wsdl);

        $response = $client->{$this->function}([
            'credentials' => [
                'user'  => config('munthe2.credentials.user'),
                'password' => config('munthe2.credentials.password')
            ]
        ]);

        $response = json_decode(json_encode($response), true);

        return $response['response']['row'];
    }
}
