<?php


namespace App\Services\Customer;


use App\Jobs\Common\CreateContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use App\Models\Customer;
use App\Services\BaseService;
use App\Traits\Jobs;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerService extends BaseService
{
    use Jobs;

    public function migrateSingleCustomer ( $customer )
    {
        $user = DB::connection( 'galaxy_db' )->table( 'user' )->where( 'id', $customer->user_id )->first();
        $contact = Customer::where('ref_id', $customer->user_id )->first();

        $data = [
            "name"          => $customer->customer_name,
            "email"         => $user->email,
            "phone"         => $customer->phone,
            "enabled"       => $user->is_blocked ? 0 : 1,
            "type"          => "customer",
            "address"       => $customer->address_line_1,
            "currency_code" => "USD",
            "company_id"    => 1,
            "ref_id"        => $customer->user_id,
            "updated_at"    => Carbon::now(),
        ];

        if ( empty( $contact ) ) {
            $data[ "create_user" ] = 'false';
            $data[ "password" ] = $customer->user_id;
            $data[ "password_confirmation" ] = $customer->user_id;
            $response = $this->ajaxDispatch( new CreateContact( $data ) );
        } /*else {
            $response = $this->ajaxDispatch( new UpdateContact( $contact, $data ) );
        }*/

        return $response;
    }

    public function migrateSingleUser ( $user )
    {
        $data = [
            'name'     => $user->username,
            'email'    => $user->email,
            'password' => $user->password_hash,
            'enabled'  => $user->is_blocked ? 0 : 1,
            'locale'   => 'en-US',
            'ref_id'   => $user->id,
        ];

        $accountingUser = User::where( 'ref_id', $user->id )->first();

        if ( empty( $accountingUser ) ) {
            $accountingUser = User::create( $data );
            $role = Role::find( 1 );
            $accountingUser->roles()->attach( $role );
            $accountingUser->companies()->attach( 1 );
        } /*else {
            $accountingUser->update( $data );
        }*/

        return $accountingUser;
    }
}
