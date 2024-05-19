<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validatedData = $validator->validated();

        $category = Category::create($validatedData);

        return response()->json([
            'message' => "Data Berhasil Disimpan",
            'data' => $category
        ], 201);
    }

    public function read()
    {
        $categories = Category::all();
        return response()->json([
            'message' => 'Data Kategori Keseluruhan',
            'data' => $categories
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|max:255|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validatedData = $validator->validated();

        $category = Category::find($id);

        if ($category) {
            $category->update($validatedData);

            return response()->json([
                'message' => 'Data dengan id: ' . $id . ' berhasil diupdate',
                'data' => $category
            ], 200);
        }

        return response()->json([
            'message' => 'Data dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();

            return response()->json([
                'message' => 'Data kategori dengan ID: ' . $id . ' berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'Data kategori dengan ID: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
