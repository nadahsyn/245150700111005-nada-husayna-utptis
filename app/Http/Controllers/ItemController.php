<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    private $file = 'items.json';

    public function index()
    {
        $path = storage_path('app/items.json');
        $items = json_decode(file_get_contents($path), true);
        return response()->json($items);
    }

    public function show($id)
    {
        $path = storage_path('app/items.json');
        $items = json_decode(file_get_contents($path), true);

        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return response()->json($item);
            }
        }

        return response()->json([
            "message" => "Item dengan ID $id tidak ditemukan"
        ], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);

        $path = storage_path('app/items.json');
        $items = json_decode(file_get_contents($path), true);

        $newItem = [
            "id" => count($items) + 1,
            "name" => $request->name,
            "price" => $request->price
        ];

        $items[] = $newItem;

        Storage::put($this->file, json_encode($items));

        return response()->json($newItem, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);

        $path = storage_path('app/items.json');
        $items = json_decode(file_get_contents($path), true);

        foreach ($items as &$item) {
            if ($item['id'] == $id) {
                $item['name'] = $request->name;
                $item['price'] = $request->price;

                Storage::put($this->file, json_encode($items));

                return response()->json($item);
            }
        }

        return response()->json([
            "message" => "Item dengan ID $id tidak ditemukan"
        ], 404);
    }

    public function patch(Request $request, $id)
    {
        $path = storage_path('app/items.json');
        $items = json_decode(file_get_contents($path), true);

        foreach ($items as &$item) {
            if ($item['id'] == $id) {

                if ($request->has('name')) {
                    $item['name'] = $request->name;
                }

                if ($request->has('price')) {
                    $item['price'] = $request->price;
                }

                Storage::put($this->file, json_encode($items));

                return response()->json($item);
            }
        }

        return response()->json([
            "message" => "Item dengan ID $id tidak ditemukan"
        ], 404);
    }

    public function destroy($id)
    {
        $path = storage_path('app/items.json');
        $items = json_decode(file_get_contents($path), true);

        foreach ($items as $index => $item) {
            if ($item['id'] == $id) {

                array_splice($items, $index, 1);

                Storage::put($this->file, json_encode($items));

                return response()->json([
                    "message" => "Item berhasil dihapus"
                ]);
            }
        }

        return response()->json([
            "message" => "Item dengan ID $id tidak ditemukan"
        ], 404);
    }
}