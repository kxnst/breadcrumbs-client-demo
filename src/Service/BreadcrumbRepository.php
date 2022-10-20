<?php

namespace App\Service;

class BreadcrumbRepository
{
    public function getBySlug(string $slug)
    {
        return json_decode(file_get_contents(__DIR__ . "/data/$slug.json"), true);
    }
}