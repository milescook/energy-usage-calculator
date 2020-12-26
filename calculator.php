<?php
require 'vendor/autoload.php';
use Domain\CalculatorService;
use Domain\Repository\TariffRepositoryJSON;
use Domain\Repository\TariffRepositoryOctopus;

$dateUsage = "2020-12-24";
$dateTariffs = "2020-12-24";
$standingCharge = 21;

$octopusConfig = 
[
    "product" => "AGILE-18-02-21",
    "productCode" => "E-1R-AGILE-18-02-21-K",
    "username" => "sk_live_N3j4z44u4vVcyygmvyQKkqK0",
    "password" => ""
];

$CalculatorService = new CalculatorService(new TariffRepositoryOctopus($octopusConfig));

$tariffPeriods = $CalculatorService->getTariffObjects($dateTariffs);



$consumptionObjects = getConsumptionObjects($dateUsage,"consumption.json");
ksort($consumptionObjects);

$costPeriods = [];
$totalCost = 0;
foreach($consumptionObjects as $time=>$thisConsumptionPeriod)
{
    $periodCost = $thisConsumptionPeriod->consumption * $tariffPeriods[$time]->value_inc_vat;
    $costPeriods[$time] = $periodCost;
    $totalCost += $periodCost;
}

$totalCost += $standingCharge;


print_r($costPeriods);
print_r("Total cost: £" . round($totalCost / 100,2) . "\n");

function getConsumptionObjects($date,$file)
{
    $consumptionPeriodObjects = [];
    $consumptionDataString = file_get_contents("consumption.json");
    if($consumptionDataString===false)
        return $consumptionPeriodObjects;

    $consumptionPeriods = json_decode($consumptionDataString);
    if($consumptionPeriods===false)
        throw new Exception("Something wrong with the json in file ".$file);

    foreach($consumptionPeriods->results as $thisConsumption)
    {
        if(strpos($thisConsumption->interval_start,$date)===0)
        {
            $time = substr($thisConsumption->interval_start,11,5);
            $consumptionPeriodObjects[$time] = $thisConsumption;
        }
    }
    return $consumptionPeriodObjects;
}

function getTariffObjects($date,$file)
{
    $tariffs = [];
    $tariffsDataString = file_get_contents($file);
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