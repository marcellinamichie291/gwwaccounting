<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Common\Item;

class Inventory extends Form
{

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $remoteAction = route('items.index');

        return view('components.form.group.inventory', compact('remoteAction'));
    }
}
