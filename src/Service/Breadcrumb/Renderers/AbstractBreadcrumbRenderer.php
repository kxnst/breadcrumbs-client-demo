<?php

namespace App\Service\Breadcrumb\Renderers;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class AbstractBreadcrumbRenderer
{
    protected ParameterBagInterface $parameterBag;

    public abstract function render();
    public abstract function renderActive();

    public function setParameterBag(ParameterBagInterface $parameterBag): void
    {
        $this->parameterBag = $parameterBag;
    }
}