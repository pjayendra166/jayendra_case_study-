<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();

        if(count($category) < 1) {
            return response()->json(['message' => 'No data found!!'], 404);
        }

        return response()->json(['message' => 'Data found!!','data' => $category], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'category created successfully'], 200);

    }
}
