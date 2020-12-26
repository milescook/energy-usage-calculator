<?php

namespace Domain;

use Domain\Repository\Consumption\IConsumptionRepository;
use Domain\Repository\Tariff\ITariffRepository;

class CalculatorService
{

    /** @var ITariffRepository $TariffRepository */
    var $TariffRepository;

    /** @var IConsumptionRepository $ConsumptionRepository */
    var $ConsumptionRepository;

    /**
     * @param ITariffRepository $TariffRepository
     * @param IConsumptionRepository $ConsumptionRepository
     */
    function __construct(ITariffRepository $TariffRepository, IConsumptionRepository $ConsumptionRepository)
    {
        $this->TariffRepository = $TariffRepository;
        $this->ConsumptionRepository = $ConsumptionRepository;
    }

    function getTariffObjects(string $date)
    {
        return $this->TariffRepository->getTariffObjects($date);
    }

    function getConsumptionObjects(string $date)
    {
        return $this->ConsumptionRepository->getConsumptionObjects($date);
    }
}