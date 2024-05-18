<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function read()
    {
        return response()->json(Category::all(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();
        Category::create($validated);

        return response()->json([
            'message' => "Data Berhasil Disimpan",
            "category"=> $validated
        ], 200);
    }

    public function showAll()
    {
        $categories = Category::all();
        return response()->json([
            'msg' => 'Data category Keseluruhan',
            'data' => $categories
        ], 200);
    }

    public function showById($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'msg' => 'Data category Berdasarkan ID ' . $id,
                'data' => $category
            ], 200);
        }

        return response()->json(['msg' => 'Data category dengan Id: ' . $id . ' Tidak Ditemukan'], 404);
    }

    public function showByName($category_name)
    {
        $categories = Category::where('name', 'like', '%' . $category_name . '%')->get();

        if ($categories->count() > 0) {
            return response()->json([
                'msg' => 'Data category Berdasarkan Nama yang mirip: ' . $category_name,
                'data' => $categories
            ], 200);
        }

        return response()->json(['msg' => 'Data category dengan Nama yang mirip: ' . $category_name . ' Tidak Ditemukan'], 404);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();
        $category = Category::find($id);

        if ($category) {
            $category->update($validated);
            return response()->json([
                'message' => "Data Berhasil Di update dengan id: {$id}",
                "category"=> $validated
            ], 200);
        }

        return response()->json("Data dengan id: {$id} tidak ditemukan", 404);
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return response()->json("Data dengan id: {$id} berhasil dihapus", 200);
        }

        return response()->json("Data dengan id: {$id} tidak ditemukan", 404);
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->where('id', $id)->first();
        
        if ($category) {
            $category->restore();
            return response()->json("Data dengan id: {$id} berhasil dipulihkan", 200);
        }

        return response()->json("Data dengan id: {$id} tidak ditemukan", 404);
    }
}
