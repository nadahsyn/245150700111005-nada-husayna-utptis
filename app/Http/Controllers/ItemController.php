<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "API Nada UTP TIS",
    version: "1.0.0"
)]

class ItemController extends Controller
{
    // ================== TEST ENDPOINT ==================
    #[OA\Get(
        path: "/api/test",
        summary: "Test endpoint",
        tags: ["Test"],
        responses: [
            new OA\Response(response: 200, description: "OK")
        ]
    )]
    public function test()
    {
        return response()->json([
            "message" => "Swagger jalan"
        ]);
    }

    // ================== DATA ==================
    private function getData()
    {
        return json_decode(file_get_contents(storage_path('app/items.json')), true);
    }

    private function saveData($data)
    {
        file_put_contents(storage_path('app/items.json'), json_encode($data, JSON_PRETTY_PRINT));
    }

    // ================== GET ALL ==================
    #[OA\Get(
        path: "/api/items",
        summary: "Get all items",
        tags: ["Items"],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function index()
    {
        return response()->json($this->getData());
    }

    // ================== GET BY ID ==================
    #[OA\Get(
        path: "/api/items/{id}",
        summary: "Get item by ID",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not Found")
        ]
    )]
    public function show($id)
    {
        $data = $this->getData();

        foreach ($data as $item) {
            if ($item['id'] == $id) {
                return response()->json($item);
            }
        }

        return response()->json([
            "message" => "Item tidak ditemukan"
        ], 404);
    }

    // ================== POST ==================
    #[OA\Post(
        path: "/api/items",
        summary: "Create item",
        tags: ["Items"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nama", "harga"],
                properties: [
                    new OA\Property(property: "nama", type: "string"),
                    new OA\Property(property: "harga", type: "integer")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Created"),
            new OA\Response(response: 400, description: "Bad Request")
        ]
    )]
    public function store(Request $request)
    {
        $data = $this->getData();

        if (!isset($request->nama) || !isset($request->harga)) {
            return response()->json([
                "message" => "Nama dan harga wajib diisi"
            ], 400);
        }

        $newItem = [
            "id" => count($data) + 1,
            "nama" => $request->nama,
            "harga" => $request->harga
        ];

        $data[] = $newItem;
        $this->saveData($data);

        return response()->json($newItem, 201);
    }

    // ================== PUT ==================
    #[OA\Put(
        path: "/api/items/{id}",
        summary: "Update full item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nama", "harga"],
                properties: [
                    new OA\Property(property: "nama", type: "string"),
                    new OA\Property(property: "harga", type: "integer")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not Found")
        ]
    )]
    public function update(Request $request, $id)
    {
        $data = $this->getData();

        foreach ($data as &$item) {
            if ($item['id'] == $id) {

                if (!isset($request->nama) || !isset($request->harga)) {
                    return response()->json([
                        "message" => "Nama dan harga wajib diisi"
                    ], 400);
                }

                $item['nama'] = $request->nama;
                $item['harga'] = $request->harga;

                $this->saveData($data);

                return response()->json($item);
            }
        }

        return response()->json([
            "message" => "Item tidak ditemukan"
        ], 404);
    }

    // ================== PATCH ==================
    #[OA\Patch(
        path: "/api/items/{id}",
        summary: "Update partial item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nama", type: "string"),
                    new OA\Property(property: "harga", type: "integer")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not Found")
        ]
    )]
    public function patch(Request $request, $id)
    {
        $data = $this->getData();

        foreach ($data as &$item) {
            if ($item['id'] == $id) {

                if (isset($request->nama)) {
                    $item['nama'] = $request->nama;
                }

                if (isset($request->harga)) {
                    $item['harga'] = $request->harga;
                }

                $this->saveData($data);

                return response()->json($item);
            }
        }

        return response()->json([
            "message" => "Item tidak ditemukan"
        ], 404);
    }

    // ================== DELETE ==================
    #[OA\Delete(
        path: "/api/items/{id}",
        summary: "Delete item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Deleted"),
            new OA\Response(response: 404, description: "Not Found")
        ]
    )]
    public function destroy($id)
    {
        $data = $this->getData();

        foreach ($data as $index => $item) {
            if ($item['id'] == $id) {

                array_splice($data, $index, 1);
                $this->saveData($data);

                return response()->json([
                    "message" => "Item berhasil dihapus"
                ]);
            }
        }

        return response()->json([
            "message" => "Item tidak ditemukan"
        ], 404);
    }
}