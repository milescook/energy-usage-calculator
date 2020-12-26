<?php

namespace Domain\Repository\Tariff;
use GuzzleHttp\Client;

class TariffRepositoryOctopus implements ITariffRepository
{
    /** @var string username */
    var $username;

    /** @var string password */
    var $password;

    /** @var string API base URI  */
    var $octopusBaseUri = "https://api.octopus.energy";

    /** @var string API base URI  */
    var $octopusEndpoint = "/v1/products/{product}/electricity-tariffs/{productCode}/standard-unit-rates";

    /** @var Client $client */
    var $client;

    /**
     * @param array<string> $config Configuration for Octopus API
     */
    public function __construct($config)
    {
        $this->validateConfig($config);

        $this->octopusEndpoint = str_ireplace("{product}",$config["product"],$this->octopusEndpoint);
        $this->octopusEndpoint = str_ireplace("{productCode}",$config["productCode"],$this->octopusEndpoint);
        $this->username = $config["username"];
        $this->password = $config["password"];

        $this->client = new Client(['base_uri' => $this->octopusBaseUri]);
    }

    function validateConfig($config)
    {
        if(!isset($config["product"]))
            throw new \Exception("Missing config item: product");

        if(!isset($config["productCode"]))
            throw new \Exception("Missing config item: productCode");

        if(!isset($config["username"]))
            throw new \Exception("Missing config item: username");

        if(!isset($config["password"]))
            throw new \Exception("Missing config item: password");
    }
    /** 
     * {@inheritdoc}
     */
    public function getTariffObjects($date) : array
    {
        $tariffObjects = [];
        $response = $this->client->request('GET', $this->octopusEndpoint, ['auth' => [$this->username, $this->password]]);

        $body = $response->getBody();
        $tariffsResponseObject = json_decode($body);
        foreach($tariffsResponseObject->results as $thisTariff)
        {
            if(strpos($thisTariff->valid_from,$date)===0)
            {
                $time = substr($thisTariff->valid_from,11,5);
                $tariffObjects[$time] = $thisTariff;
            }
        }
        if(count($tariffObjects)<48)
        {
            $response = $this->client->request('GET', $tariffsResponseObject->next, ['auth' => [$this->username, $this->password]]);

            $body = $response->getBody();
            $tariffsResponseObject = json_decode($body);
            foreach($tariffsResponseObject->results as $thisTariff)
            {
                if(strpos($thisTariff->valid_from,$date)===0)
                {
                    $time = substr($thisTariff->valid_from,11,5);
                    $tariffObjects[$time] = $thisTariff;
                }
            }
        }
        ksort($tariffObjects);

        return $tariffObjects;
    }
}
