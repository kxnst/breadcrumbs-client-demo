<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

// пустий контролер для посилань з хлібних крихт
class HoroController extends AbstractController
{
    #[Route(path: 'horo/main', name: 'horo_names_main')]
    public function main()
    {

    }

    #[Route(path: 'horo/names', name: 'horo_names')]
    public function names()
    {

    }

    #[Route(path: 'horo/main/{theme}', name: 'horo_names_theme')]
    public function bySex(string $theme)
    {

    }

    #[Route(path: 'horo/main/{theme}/{letter}', name: 'horo_names_theme_letter')]
    public function byThemeAndLetter(string $theme, string $letter)
    {

    }
}
