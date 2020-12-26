<?php

namespace Domain\Repository;

class TariffRepositoryOctopus implements ITariffRepository
{
    /** @var string username */
    var $username;

    /** @var string password */
    var $password;

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
        $this->username = $config["username"];
        $this->password = $config["password"];
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

        return $tariffObjects;
    }
}
