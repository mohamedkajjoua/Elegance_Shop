<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{

    public function index()
    {
        return Categorie::latest()->get();
    }


    public function show(Categorie $Categorie)
    {
        return $Categorie;
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:categories,name',
        ]);

        $Categorie = Categorie::create($data);

        return response()->json($Categorie, 201);
    }


    public function update(Request $request, Categorie $Categorie)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:categories,name,' . $Categorie->id,
        ]);

        $Categorie->update($data);

        return response()->json($Categorie);
    }


    public function destroy(Categorie $Categorie)
    {
        $Categorie->delete();

        return response()->json(['message' => 'Categorie deleted successfully']);
    }
}
