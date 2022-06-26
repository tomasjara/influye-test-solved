<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemsController extends Controller
{
    public function getItems()
    {
        $items = Item::all();
        return $items;
    }
}
