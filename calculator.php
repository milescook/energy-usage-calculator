<?php
require 'vendor/autoload.php';
use Domain\CalculatorService;
use Domain\Repository\Consumption\ConsumptionRepositoryOctopus;
use Domain\Repository\Tariff\TariffRepositoryOctopus;


$argDate = $argv[1];

$dateUsage = $argDate;
$dateTariffs = $argDate;

$standingCharge = 21;

$octopusConfigTariffs = 
[
    "product" => "AGILE-18-02-21",
    "productCode" => "E-1R-AGILE-18-02-21-K",
    "username" => getenv("ENERGY_CALC_APIKEY"),
    "password" => ""
];

$octopusConfigConsumption = 
[
    "mpan" => getenv("ENERGY_CALC_MPAN"),
    "serial" => getenv("ENERGY_CALC_SERIAL"),
    "username" => getenv("ENERGY_CALC_APIKEY"),
    "password" => ""
];

$CalculatorService = 
    new CalculatorService(
        new TariffRepositoryOctopus($octopusConfigTariffs), 
        new ConsumptionRepositoryOctopus($octopusConfigConsumption));

$tariffPeriods = $CalculatorService->getTariffObjects($dateTariffs);
print_r($tariffPeriods);
$consumptionObjects = $CalculatorService->getConsumptionObjects($dateUsage);

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


