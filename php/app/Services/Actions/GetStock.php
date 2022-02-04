<?php

namespace App\Services\Actions;

use App\Services\Export\DTO\DataTransferObject;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GetStock
 *
 * @package App\Services\Actions
 */
class GetStock
{
    /**
     * @var string
     */
    protected $wsdl;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var DataTransferObject
     */
    protected $dataTransferObject;

    /**
     * GetStock constructor.
     *
     * @param string $wsdl
     * @param string $user
     * @param string $password
     * @param DataTransferObject $dataTransferObject
     */
    public function __construct(string $wsdl, string $user, string $password, DataTransferObject $dataTransferObject)
    {
        $this->wsdl = $wsdl;
        $this->user = $user;
        $this->password = $password;
        $this->dataTransferObject = $dataTransferObject;
    }

    /**
     * @param string $variantid
     *
     * @return Model|null
     * @throws \SoapFault
     */
    public function getByVariantid(string $variantid): ?Model
    {
        $client = new \SoapClient(base_path() . "/EA6183RA.wsdl.xml", [
            'location' => $this->wsdl,
            'trace' => true,
        ]);

        $response = $client->getstock([
            'credentials' => [
                'user' => $this->user,
                'password' => $this->password,
            ],
            'request' => [
                'variantid' => $variantid,
            ],
        ]);

        $response = json_decode(json_encode($response), true);

        if ($response) {
            return $this->dataTransferObject->getEntity($response['response']['grpstock']);
        }
    }
}
