<?php

namespace App\Console\Commands;

use App\Models\Inventory;
use App\Services\Vehicle\VehicleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VehicleMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:vehicle {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all vehicles from Galaxy Shipping to accounting system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        // Get all companies
        $lastSyncedId = Inventory::max('ref_id') ?? 0;
        $vehicles = DB::connection( 'galaxy_db' )
            ->table( 'vehicle' )
            ->where('vehicle_is_deleted',  '=', 0)
            ->where('created_at', '>=', '2022-01-01');
        if( ! $this->option('all') ) {
            $vehicles->where('id', '>', $lastSyncedId);
        }

        foreach ( $vehicles->cursor() as $vehicle ) {
            try {
                app( VehicleService::class )->migrateSingleVehicle( $vehicle );
                $this->info( "{$vehicle->vin} migrated successfully." );
            } catch ( \Exception $e ) {
                $this->error( $e->getMessage() );
            }
        }
    }


}
