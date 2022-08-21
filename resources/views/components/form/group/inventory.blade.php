<x-form.group.select
    name="item_id"
    remote
    :remote_action="$remoteAction"
    label="Inventory"
    :options="$options"
    required="{{ $required }}"
    not-required="{{ $notRequired }}"
    model="form.item_id"
    form-group-class="{{ $formGroupClass }}"
/>
