<?php

namespace spec\Domain;

use Domain\CalculatorService;
use Domain\Repository\ITariffRepository;
use PhpSpec\ObjectBehavior;

class CalculatorServiceSpec extends ObjectBehavior
{
    function let(ITariffRepository $TariffRepository)
    {
        $this->beConstructedWith($TariffRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CalculatorService::class);
    }
}
