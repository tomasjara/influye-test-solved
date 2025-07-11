<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsTransaction extends Model
{
    protected $table = 'items_transaction';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $casts = ['details' => 'array'];
    protected $fillable = ['item_id', 'quantity', 'details'];
}
