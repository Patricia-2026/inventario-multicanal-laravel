<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'products';

    protected $fillable = ['nombre', 'precio', 'stock'];
    
    protected $casts = [
        'precio' => 'decimal:2', 
        'stock' => 'integer'
    ];
}
