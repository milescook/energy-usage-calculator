<?php

namespace Domain\Repository;

interface ITariffRepository
{
    /**
     * @return array<mixed> Tariff Objects indexed by time
     */
    public function getTariffObjects() : array;
}