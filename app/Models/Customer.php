<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'contacts';

    protected $fillable = ['company_id', 'type', 'name', 'email', 'user_id', 'tax_number', 'phone', 'address', 'website', 'currency_code', 'reference', 'enabled', 'ref_id'];
}
