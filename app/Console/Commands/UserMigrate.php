<?php

namespace App\Console\Commands;

use App\Models\Common\Item;
use App\Services\Customer\CustomerService;
use App\Services\Vehicle\VehicleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all users from Galaxy Shipping to accounting system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        // Get all companies
        $customerIds = DB::connection( 'galaxy_db' )->table( 'customer' )->pluck('user_id');
        $users = DB::connection( 'galaxy_db' )->table( 'user' )->whereNotIn('id', $customerIds)->cursor();

        foreach ( $users as $user ) {
            try {
                app( CustomerService::class )->migrateSingleUser( $user );
                $this->info( "User: {$user->id} migrated successfully." );
            } catch ( \Exception $e ) {
                $this->error( $e->getMessage() );
            }

        }
    }


}
