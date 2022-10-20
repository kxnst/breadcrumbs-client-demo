<?php

namespace App\Controller;

use App\Service\Breadcrumb\BreadcrumbService;
use App\Service\Breadcrumb\Renderers\LinkBreadcrumbRenderer;
use App\Service\Breadcrumb\Renderers\TextBreadcrumbRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

trait InitsBreadcrumbsTrait
{
    protected function initBreadcrumbs(BreadcrumbService $service, Request $request)
    {
        /** @var RouterInterface $router */
        $router = $this->container->get('router');
        $routeCollection = $router->getRouteCollection();

        $service->setRouteCollection($routeCollection);
        $service->setRequest($request);
        $service->setLinkRenderer(LinkBreadcrumbRenderer::class);
        $service->setSpanRenderer(TextBreadcrumbRenderer::class);
        $this->initBreadcrumbResolvers($service);
        $this->initBreadcrumbReplacements($service);
    }

    protected abstract function initBreadcrumbResolvers(BreadcrumbService $service);

    protected abstract function initBreadcrumbReplacements(BreadcrumbService $service);
}