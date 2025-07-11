<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['title', 'unit', 'quantity'];
}
