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
        $inventoryItems = Item::enabled()->where('type', 'product')->orderBy('name')->pluck('name', 'id')->toArray();

        return view('components.form.group.inventory', compact('inventoryItems'));
    }
}
