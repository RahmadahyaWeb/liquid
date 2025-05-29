<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Helpers\BreadcrumbHelper;

class BreadcrumbComposer
{
    public function compose(View $view)
    {
        $view->with('breadcrumbs', BreadcrumbHelper::get());
    }
}
