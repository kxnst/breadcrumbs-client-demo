<?php

namespace App\Service\Breadcrumb\Renderers;

class TextBreadcrumbRenderer extends AbstractBreadcrumbRenderer
{
    public function render(): string
    {
        return "<span class='breadcrumb-span'>{$this->parameterBag->get('text')}</span>";
    }

    public function renderActive(): string
    {
        return "<span class='breadcrumb-span active'>{$this->parameterBag->get('text')}</span>";
    }
}