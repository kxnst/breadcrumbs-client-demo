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
        //$request->attributes->set('_route', 'horo_names');
        $this->initBreadcrumbs($breadcrumbService, $request);
        $breadcrumbs = $breadcrumbService->render($bag, $repository->getBySlug($breadcrumb));

        return $this->render('index.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    protected function initBreadcrumbResolvers(BreadcrumbService $service)
    {
        $service->setResolvers(
            [
                // route name
                'horo_names_theme' => [
                    // set - адмін встановив значення роут параметра через адмінку,
                    // unset - значення роут параметра потрібно підставити самому на клієнті
                    // пи.си - писалось швидко і бездумно, скоріш за все на клієнті не потрібно буде генерити такі
                    // монструозні конструкції, бо вся логіка - це дістати параметр з необхідного набору (set/unset)
                    'unset' => [
                        'theme' => [$this->horoResolver, 'resolveTheme']
                    ],
                    'set' => [
                        'theme' => [$this->horoResolver, 'resolveThemeFromValue'],
                        'letter' => [$this->horoResolver, 'resolveLetterFromValue']
                    ],
                ],
                'horo_names_theme_letter' => [
                    'unset' => [
                        'theme' => [$this->horoResolver, 'resolveTheme']
                    ],
                    'set' => [
                        'theme' => [$this->horoResolver, 'resolveThemeFromValue'],
                        'letter' => [$this->horoResolver, 'resolveLetterFromValue']
                    ],
                ],
            ]
        );
    }

    // реплейсменти в тексті
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