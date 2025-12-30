<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class DoctorComposer
{
    public function compose(View $view)
    {
        // This is a view composer, not a response modifier
        // We'll pass data to the view instead
        $view->with('_forceDoctype', true);
    }
}