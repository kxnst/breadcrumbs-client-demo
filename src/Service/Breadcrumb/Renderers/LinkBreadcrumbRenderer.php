<?php

namespace App\Service\Breadcrumb\Renderers;

class LinkBreadcrumbRenderer extends AbstractBreadcrumbRenderer
{
    public function render(): string
    {
        return "<a class='breadcrumb-a' href='{$this->parameterBag->get('link')}'>{$this->parameterBag->get('text')}</a>";
    }

    public function renderActive(): string
    {
        return "<a class='breadcrumb-a active' href='{$this->parameterBag->get('link')}'>{$this->parameterBag->get('text')}</a>";
    }
}