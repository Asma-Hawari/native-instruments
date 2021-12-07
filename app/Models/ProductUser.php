<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUser extends  Model
{
    use HasFactory;

    protected $table='product_user';


    use HasFactory;
    protected $fillable = [
        'user_id',
        'sku'
    ];

}