<?php

namespace App\Service\Breadcrumb\Resolvers;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class HoroResolver
{
    public function resolveTheme(ParameterBag $bag): string
    {
        return $bag->get('theme') . '_theme';
    }
}