<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return response()->json($packages); // Alternatively: return view('packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price_per_kg' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        $package = Package::create($request->all());
        return response()->json(['message' => 'Package created', 'data' => $package], 201);
    }
}
