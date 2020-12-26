<?php

namespace Domain\Repository;

class TariffRepositoryOctopus implements ITariffRepository
{
    /** @var string apiKey */
    var $apiKey;
    
    /** @var string API Endpoint  */
    var $octopusEndpoint 
    = "https://api.octopus.energy/v1/products/{product}/electricity-tariffs/{productCode}/standard-unit-rates/";

    /**
     * @param array<string> $config Configuration for Octopus API
     */
    public function __construct($config)
    {
        $this->validateConfig($config);

        $this->octopusEndpoint = str_ireplace("{product}",$config["product"],$this->octopusEndpoint);
        $this->octopusEndpoint = str_ireplace("{productCode}",$config["productCode"],$this->octopusEndpoint);
        $this->apiKey = $config["apiKey"];
    }

    function validateConfig($config)
    {
        if(!isset($config["product"]))
            throw new \Exception("Missing config item: product");

        if(!isset($config["productCode"]))
            throw new \Exception("Missing config item: productCode");

        if(!isset($config["apiKey"]))
            throw new \Exception("Missing config item: apiKey");
    }
    /** 
     * {@inheritdoc}
     */
    public function getTariffObjects() : array
    {
        $tariffObjects = [];

        return $tariffObjects;
    }
}
