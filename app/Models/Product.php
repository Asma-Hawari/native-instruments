<?php

/**
 *  * @author Eng.Asma Hawari
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends  Model
{


    use HasFactory;
    protected $fillable = [
        'sku',
        'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class,'product_user');
    }
}