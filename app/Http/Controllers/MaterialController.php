<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MaterialType;

class MaterialController extends Controller
{
    public function createMaterial()
    {
        return view('material.create');
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255',Rule::unique('material_types', 'name'),],
        ]);

        $material = MaterialType::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'id'   => $material->id,
            'name' => $material->name,
        ]);
    }
}
