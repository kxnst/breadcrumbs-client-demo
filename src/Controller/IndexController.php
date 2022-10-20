<?php

namespace App\Controller;

use App\Service\Breadcrumb\BreadcrumbService;
use App\Service\Breadcrumb\Resolvers\HoroResolver;
use App\Service\BreadcrumbRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private HoroResolver $horoResolver
    )
    {

    }

    use InitsBreadcrumbsTrait;

    #[Route(path: 'test/{breadcrumb}')]
    public function action(
        BreadcrumbRepository $repository,
        BreadcrumbService    $breadcrumbService,
        string               $breadcrumb,
        Request              $request
    ): Response
    {
        $request->setLocale('uk');
        $bag = new ParameterBag(['theme' => 'male']);
        $this->initBreadcrumbs($breadcrumbService, $request);
        $breadcrumbs = $breadcrumbService->render($bag, $repository->getBySlug($breadcrumb));

        return $this->render('index.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    protected function initBreadcrumbResolvers(BreadcrumbService $service)
    {
        $service->setResolvers(
            [
                'horo_names_theme' => [
                    'theme' => [$this->horoResolver, 'resolveTheme']
                ],
                'horo_names_theme_letter' => [
                    'theme' => [$this->horoResolver, 'resolveTheme']
                ],
            ]
        );
    }

    protected function initBreadcrumbReplacements(BreadcrumbService $service)
    {
        $service->setReplacements(
            [
                'theme_horo' => ['{{theme}}' => 'theme'],
                'theme_letter_horo' => [
                    '{{theme}}' => 'theme',
                    '{{letter}}' => 'letter',
                ],
            ]
        );
    }
}