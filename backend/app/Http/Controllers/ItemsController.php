<?php

namespace App\Http\Controllers;

use App\Http\Models\Item;
use App\Http\Models\ItemsTransaction;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function getItems()
    {
        $items = Item::all();
        return $items;
    }

    public function createItem(Request $request)
    {
        $data = $request->validate([
            'title' => 'string',
            'unit'  => 'string',
        ]);

        $item = Item::create([
            'title' => $data['title'],
            'unit' => $data['unit'],
            'quantity' => 0
        ]);
        return response()->json($item, 201);
    }

    public function deleteItem($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }

    public function addStock(Request $request, $id_item)
    {
        try {
            
            $item = Item::find($id_item);
            if (!$item) {
                return response()->json(['message' => 'Item not found'], 404);
            }

            if ($item->quantity === 0) {
                return response()->json(['message' => 'The quantity of the item cannot be zero'], 400);
            }

            $dataTransaction = $request->all();
            echo ("Data: " . json_encode($dataTransaction['transactions']['quantity']) . "\n");
            echo( $id_item);

            $itemTransaction = ItemsTransaction::create([
                'item_id' => $id_item,
                'quantity' => $dataTransaction['transactions']['quantity'],
                'details' => $dataTransaction['transactions']['details']
            ]);

            $item->quantity += $dataTransaction['transactions']['quantity'];
            $item->save();

            return response()->json(['message' => 'Stock updated successfully', 'item' => $item], 200);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getTransactions($id_item)
    {
        $item = Item::find($id_item);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $transactions = ItemsTransaction::where('item_id', $id_item)->get();
        return response()->json($transactions, 200);
    }
}
