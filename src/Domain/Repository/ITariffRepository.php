<?php

namespace Domain\Repository;

interface ITariffRepository
{
    /**
     * @param string $date
     * @return array<mixed> Tariff Objects indexed by time
     */
    public function getTariffObjects(string $date) : array;
}