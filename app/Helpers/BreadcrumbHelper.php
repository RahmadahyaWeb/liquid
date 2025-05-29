<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class BreadcrumbHelper
{
    public static function get()
    {
        $routeName = Route::currentRouteName();
        $jsonPath = resource_path('data/breadcrumbs.json');

        if (!File::exists($jsonPath)) return [];

        $data = json_decode(File::get($jsonPath), true);

        return $data[$routeName] ?? [];
    }
}
