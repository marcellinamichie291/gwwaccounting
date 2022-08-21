@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
@endpush
<x-layouts.print>
    <x-slot name="title">
        {{ trans_choice('general.invoices', 1) . ': ' . $invoice->document_number }}
    </x-slot>

    <x-slot name="content">
        <x-documents.template.ddefault
            type="invoice"
            :document="$invoice"
        />
    </x-slot>
</x-layouts.print>
