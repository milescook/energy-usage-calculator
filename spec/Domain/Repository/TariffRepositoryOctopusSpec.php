<?php

namespace spec\Domain\Repository;

use Domain\Repository\TariffRepositoryOctopus;
use PhpSpec\ObjectBehavior;

class TariffRepositoryOctopusSpec extends ObjectBehavior
{
    function let()
    {
        $config = 
        [
            "product" => "AGILE-18-02-21",
            "productCode" => "E-1R-AGILE-18-02-21-K",
            "username" => "dave",
            "password" => "wibble"
        ];

        $this->beConstructedWith($config);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(TariffRepositoryOctopus::class);
    }
}
