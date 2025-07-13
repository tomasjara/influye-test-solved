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
        try {
            $data = $request->all();

            if (!isset($data['title'])) {
                return response()->json(['message' => 'Title is required'], 400);
            }

            $item = Item::create([
                'title' => $data['title'],
                'unit' => $data['unit'],
                'quantity' => 0
            ]);

            return $this->getItems();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating item', $e], 500);
        }
    }

    public function deleteItem($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
        return $this->getItems();
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

            $itemTransaction = ItemsTransaction::create([
                'item_id' => $id_item,
                'quantity' => $dataTransaction['transactions']['quantity'],
                'details' => $dataTransaction['transactions']['details']
            ]);

            $item->quantity += $dataTransaction['transactions']['quantity'];
            $item->save();

            return $this->getItems();
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
