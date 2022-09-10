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

        $arrivedDate = '';
        $ve = DB::connection( 'galaxy_db' )
            ->table( 'vehicle_export' )
            ->where('vehicle_id', $inventory->id)
            ->where('vehicle_export_is_deleted', '<>', 1)
            ->first();
        if( $ve ) {
            $export = DB::connection( 'galaxy_db' )
                ->table( 'export' )
                ->where('id', $ve->export_id)
                ->where('export_is_deleted', '<>', 1)
                ->first();
            if( $export ) {
                $arrivedDate = $export->arrival_date ?? '';
            }
        }

        $data = [
            'company_id'     => 1,
            'name'           => $inventory->vin . ' | ' . $inventory->lot_number,
            'description'    => $inventory->make . ' ' . $inventory->model . ' ' . $inventory->year,
            'sale_price'     => (float)preg_replace('/[^0-9.]+/', '', $inventory->value),
            'purchase_price' => (float)preg_replace('/[^0-9.]+/', '', $inventory->value),
            'quantity'       => 1,
            'category_id'    => 6,
            'type'           => 'product',
            'ref_id'         => $inventory->id,
            'misc'           => [
                'id'            => $inventory->id,
                'vin'           => $inventory->vin,
                'lot_number'    => $inventory->lot_number,
                'year'          => $inventory->year,
                'make'          => $inventory->make,
                'model'         => $inventory->model,
                'color'         => $inventory->color,
                'arrived_date'  => $arrivedDate,
                'buyer_id'      => $inventory->license_number,
                'auction'       => $inventory->auction_name,
                'purchase_date' => $inventory->purchase_date,
            ],
        ];

        if ( empty($item) ) {
            $item = Inventory::create($data);
        } else {
            $item->update($data);
        }

        return $item;
    }

}
