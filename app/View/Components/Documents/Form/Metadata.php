<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\Documents\Form as Component;
use App\Models\Common\Item;

class Metadata extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $inventoryItems = Item::enabled()->where('category_id', 6)->orderBy('name')->pluck('name', 'id');

        return view('components.documents.form.metadata', compact('inventoryItems'));
    }
}
