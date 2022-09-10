<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [ 'ref_id', 'company_id', 'name', 'description', 'sale_price', 'purchase_price', 'category_id', 'misc' ];

    protected $casts = [
        'misc' => 'array',
    ];
}
