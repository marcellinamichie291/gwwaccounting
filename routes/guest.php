<?php

use App\Models\Document\Document;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * 'guest' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapGuestRoutes
 * @see \modules\PaypalStandard\Routes\guest.php for module example
 */

Route::get('{company_id}/galaxy/invoices/{id}/pdf', function($companyId, $id) {
    $invoice = Document::withoutGlobalScope('App\Scopes\Document')->with([
        'company' => function ($query) {
            $query->withoutGlobalScopes([\App\Scopes\Company::class]);
        },'items' => function ($query) {
            $query->withoutGlobalScopes();
        },
        'totals_sorted' => function ($query) {
            $query->withoutGlobalScopes();
        },
        'transactions' => function ($query) {
            $query->withoutGlobalScopes();
        }
    ])->where('company_id', $companyId)
        ->find($id);

    event(new \App\Events\Document\DocumentPrinting($invoice));

    $currency_style = true;

    $view = view("sales.invoices.galaxy_print_default", compact('invoice', 'currency_style'))->render();

    $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    $pdf = app('dompdf.wrapper');
    $pdf->loadHTML($html);

    //$pdf->setPaper('A4', 'portrait');

    $file_name = Str::slug($invoice->document_number, '-', language()->getShortCode());

    return $pdf->stream($file_name);
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\Login@create')->name('login');
    Route::post('login', 'Auth\Login@store')->name('login.store');

    Route::get('forgot', 'Auth\Forgot@create')->name('forgot');
    Route::post('forgot', 'Auth\Forgot@store')->name('forgot.store');

    //Route::get('reset', 'Auth\Reset@create');
    Route::get('reset/{token}', 'Auth\Reset@create')->name('reset');
    Route::post('reset', 'Auth\Reset@store')->name('reset.store');

    Route::get('register/{token}', 'Auth\Register@create')->name('register');
    Route::post('register', 'Auth\Register@store')->name('register.store');

    // Automatic login for olfat system user
    Route::get('galaxy/login/{id}', function($id) {
        $user = \App\Models\Auth\User::where('ref_id', $id)->first();

        if ( empty($user) ) {
            $user = DB::connection( 'galaxy_db' )->table( 'user' )->where( 'id', $id )->first();
            if ($user) {
                app( App\Services\Customer\CustomerService::class )->migrateSingleUser( $user );
            }
            $user = \App\Models\Auth\User::where('ref_id', $id)->first();
        }

        if ( $user ) {
            if ( ! auth()->loginUsingId($user->id) ) {
                return redirect()->route('login');
            }

            return redirect()->route($user->landing_page, ['company_id' => 1]);
        }

        return redirect()->route('login');
    });
});

Route::get('/', function () {
    return redirect()->route('login');
});
