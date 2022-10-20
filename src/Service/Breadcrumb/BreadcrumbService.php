<?php

namespace App\Service\Breadcrumb;

use App\Service\Breadcrumb\Renderers\AbstractBreadcrumbRenderer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouteCollection;

class BreadcrumbService
{
    protected string $linkRenderer;

    protected string $spanRenderer;

    protected array $resolvers;

    protected array $replacements;

    protected RouteCollection $routeCollection;

    protected Request $request;

    public function __construct(
        protected ParameterBagInterface $parameterBag,
        protected UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function render(ParameterBagInterface $parameterBag, array $breadcrumb)
    {
        $breadcrumbs = [];
        $breadcrumbsOutput = [];
        $this->expandBreadcrumb($breadcrumb['head'], $breadcrumbs);

        foreach ($breadcrumbs as $breadcrumb) {
            $isActive = false;
            $currentRouteName = $this->request->attributes->get('_route');

            $preparedRouteVariables = [];

            $routeName = $breadcrumb['route_name'] ?? null;
            if ($routeName) {
                if($currentRouteName == $routeName) {
                    $isActive = true;
                }
                $compiledRoute = $this->routeCollection->get($routeName)->compile();
                $routeVariables = $compiledRoute->getVariables();
                foreach ($routeVariables as $routeVariable) {
                    if (isset($breadcrumb['values']) && isset($breadcrumb['values'][$routeVariable])) {
                        [$resolver, $method] = $this->resolvers[$routeName]['set'][$routeVariable];
                        $value = $resolver->$method($parameterBag, $breadcrumb['values'][$routeVariable]);
                        $parameterBag->set($routeVariable, $value);
                    } else {
                        [$resolver, $method] = $this->resolvers[$routeName]['unset'][$routeVariable];
                        $value = $resolver->$method($parameterBag);
                    }
                    $preparedRouteVariables[$routeVariable] = $value;
                }
                $parameterBag->set('link', $this->urlGenerator->generate($routeName, $preparedRouteVariables));
            }
            $text = $breadcrumb['translations'][$this->request->getLocale()];

            if (isset($this->replacements[$breadcrumb['slug']])) {
                $replacedText = array_map(
                    function ($value) use ($parameterBag) {
                        return $parameterBag->get($value);
                    },
                    array_values($this->replacements[$breadcrumb['slug']]));
                $text = str_replace(
                    array_keys($this->replacements[$breadcrumb['slug']]),
                    $replacedText,
                    $breadcrumb['translations'][$this->request->getLocale()]
                );
            }

            $parameterBag->set('text', $text);
            /** @var AbstractBreadcrumbRenderer $renderer */
            $renderer = new ($routeName ? $this->linkRenderer : $this->spanRenderer)();
            $renderer->setParameterBag($parameterBag);
            $breadcrumbsOutput[] = $isActive ? $renderer->renderActive() : $renderer->render();
            if($isActive) {
                break;
            }
        }

        return $breadcrumbsOutput;
    }

    public function setLinkRenderer(string $linkRenderer): void
    {
        $this->linkRenderer = $linkRenderer;
    }

    public function setSpanRenderer(string $spanRenderer): void
    {
        $this->spanRenderer = $spanRenderer;
    }

    protected function expandBreadcrumb(array $node, array &$result)
    {
        $result[] = $node;
        if ($node['child']) {
            $this->expandBreadcrumb($node['child'], $result);
        }
    }

    public function setRouteCollection(RouteCollection $routeCollection): void
    {
        $this->routeCollection = $routeCollection;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setResolvers(array $resolvers): void
    {
        $this->resolvers = $resolvers;
    }

    public function setReplacements(array $replacements): void
    {
        $this->replacements = $replacements;
    }
}
