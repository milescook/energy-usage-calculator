<?php

namespace Domain\Repository\Consumption;

interface IConsumptionRepository
{
    /**
     * @param string $date
     * @return array<mixed> Consumption Objects indexed by time
     */
    public function getConsumptionObjects(string $date) : array;
}