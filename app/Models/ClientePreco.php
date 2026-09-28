<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientePreco extends Model
{
    protected $table = 'cliente_precos';

    protected $fillable = ['cliente_id', 'product_id', 'price', 'original_price'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
