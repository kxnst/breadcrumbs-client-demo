<?php

namespace App\Service\Breadcrumb\Resolvers\Initiators;

abstract class AbstractInitiator
{
    public abstract function getInitiators(): array;
}
