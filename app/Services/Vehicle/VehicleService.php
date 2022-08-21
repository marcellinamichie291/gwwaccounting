<?php


namespace App\Services\Vehicle;


use App\Models\Inventory;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class VehicleService extends BaseService
{
    public function migrateSingleVehicle( $inventory )
    {
        $item = Inventory::where('ref_id', $inventory->id)->first();

        $data = [
            'company_id'     => 1,
            'name'           => $inventory->vin,
            'description'    => $inventory->make . ' ' . $inventory->model . ' ' . $inventory->year,
            'sale_price'     => (float)preg_replace('/[^0-9.]+/', '', $inventory->value),
            'purchase_price' => (float)preg_replace('/[^0-9.]+/', '', $inventory->value),
            'quantity'       => 1,
            'category_id'    => 6,
            'type'           => 'product',
            'ref_id'         => $inventory->id,
        ];

        if ( empty($item) ) {
            $item = Inventory::create($data);
        } else {
            $item->update($data);
        }

        return $item;
    }

}
