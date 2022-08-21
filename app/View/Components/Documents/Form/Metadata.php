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
        $inventoryItems = Item::enabled()->where('type', 'product')->orderBy('name')->orderBy('id', 'desc');
        if( optional($this->document)->item_id ) {
            $inventoryItems->where('id', optional($this->document)->item_id);
        }
        $inventoryItems = $inventoryItems->limit(10)->pluck('name', 'id')->toArray();

        return view('components.documents.form.metadata', compact('inventoryItems'));
    }
}
