<?php

namespace Domain;

use Domain\Repository\ITariffRepository;

class CalculatorService
{

    /** @var ITariffRepository $TariffRepository */
    var $TariffRepository;

    /**
     * @param ITariffRepository $TariffRepository
     */
    function __construct(ITariffRepository $TariffRepository)
    {
        $this->TariffRepository = $TariffRepository;
    }

    function getTariffObjects(string $date)
    {
        return $this->TariffRepository->getTariffObjects($date);
    }
}