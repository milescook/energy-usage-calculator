<?php

$dateUsage = "2020-12-24";
$dateTariffs = "2020-12-27";
$standingCharge = 21;


$tariffPeriods = getTariffObjects($dateTariffs,"tariffs.json");
//$tariffPeriods += getTariffObjects($date,"tariffs2.json");

ksort($tariffPeriods);

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