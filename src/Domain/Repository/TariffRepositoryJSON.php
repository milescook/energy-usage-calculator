<?php

namespace Domain\Repository;

class TariffRepositoryJSON implements ITariffRepository
{
    /** @var string filepath */
    var $filepath;
    /**
     * @param array<string> $config Configuration for Octopus API
     */
    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    
    /** 
     * {@inheritdoc}
     */
    public function getTariffObjects($date) : array
    {
        
        $tariffs = [];
        $tariffsDataString = file_get_contents($this->filepath);
        $tariffPeriods = json_decode($tariffsDataString);

        foreach($tariffPeriods->results as $thisTariff)
        {
            if(strpos($thisTariff->valid_from,$date)===0)
            {
                $time = substr($thisTariff->valid_from,11,5);
                $tariffs[$time] = $thisTariff;
            }
        }

        return $tariffs;

    }
}
