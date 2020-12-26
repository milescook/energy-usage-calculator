<?php

namespace Domain\Repository\Consumption;
use GuzzleHttp\Client;

class ConsumptionRepositoryOctopus implements IConsumptionRepository
{
    /** @var string username */
    var $username;

    /** @var string password */
    var $password;

    /** @var string API base URI  */
    var $octopusBaseUri = "https://api.octopus.energy";

    /** @var string API base URI  */
    var $octopusEndpoint = "/v1/electricity-meter-points/{mpan}/meters/{serial}/consumption/";

    /** @var Client $client */
    var $client;

    /**
     * @param array<string> $config Configuration for Octopus API
     */
    public function __construct($config)
    {
        $this->validateConfig($config);

        $this->octopusEndpoint = str_ireplace("{mpan}",$config["mpan"],$this->octopusEndpoint);
        $this->octopusEndpoint = str_ireplace("{serial}",$config["serial"],$this->octopusEndpoint);
        $this->username = $config["username"];
        $this->password = $config["password"];

        $this->client = new Client(['base_uri' => $this->octopusBaseUri]);
    }

    function validateConfig($config)
    {
        if(!isset($config["mpan"]))
            throw new \Exception("Missing config item: mpan");

        if(!isset($config["serial"]))
            throw new \Exception("Missing config item: serial");

        if(!isset($config["username"]))
            throw new \Exception("Missing config item: username");

        if(!isset($config["password"]))
            throw new \Exception("Missing config item: password");
    }
    /** 
     * {@inheritdoc}
     */
    public function getConsumptionObjects($date) : array
    {
        $consumptionObjects = [];
        $response = $this->client->request('GET', $this->octopusEndpoint, ['auth' => [$this->username, $this->password]]);

        $body = $response->getBody();
        $consumptionResponseObject = json_decode($body);
        
        foreach($consumptionResponseObject->results as $thisConsumption)
        {
            if(strpos($thisConsumption->interval_start,$date)===0)
            {
                $time = substr($thisConsumption->interval_start,11,5);
                $consumptionObjects[$time] = $thisConsumption;
            }
        }
        
        ksort($consumptionObjects);

        return $consumptionObjects;
    }
}
