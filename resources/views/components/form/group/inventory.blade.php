<x-form.group.select
    name="item_id"
    label="Inventory"
    :options="$inventoryItems"
    required="{{ $required }}"
    not-required="{{ $notRequired }}"
    model="form.item_id"
    form-group-class="{{ $formGroupClass }}"
/>
