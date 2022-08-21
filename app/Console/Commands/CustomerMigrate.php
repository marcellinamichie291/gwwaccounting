<?php

namespace App\Console\Commands;

use App\Models\Common\Item;
use App\Services\Customer\CustomerService;
use App\Services\Vehicle\VehicleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CustomerMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all customers from Galaxy Shipping to accounting system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        // Get all companies
        $lastSyncedId = Item::max('ref_id') ?? 0;
        $customers = DB::connection( 'galaxy_db' )
            ->table( 'customer' )
            ->where('is_deleted',  '=', 0)
            ->where('user_id', '>', $lastSyncedId)
            ->cursor();

        foreach ( $customers as $customer ) {
            try {
                app( CustomerService::class )->migrateSingleCustomer( $customer );
                $this->info( "{$customer->user_id} migrated successfully." );
            } catch ( \Exception $e ) {
                $this->error( $e->getMessage() );
            }

        }
    }


}
