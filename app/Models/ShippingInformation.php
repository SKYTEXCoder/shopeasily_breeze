<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingInformation extends Model
{
    use HasFactory;

    protected $table = 'shipping_information';

    protected $fillable = [
        'user_id',
        'is_primary',
        'label',
        'street_address',
        'address_line_2',
        'city_or_regency',
        'state',
        'province',
        'zip_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
