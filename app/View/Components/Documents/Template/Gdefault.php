<?php

namespace App\View\Components\Documents\Template;

use App\Abstracts\View\Components\Documents\Template as Component;

class Gdefault extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.documents.template.galaxy_default');
    }
}
