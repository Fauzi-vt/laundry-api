<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::select('services.*')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->orderBy('categories.name')
            ->orderBy('services.name')
            ->get();

        return response()->json([
            'message' => 'success',
            'data'    => $services,
        ]);
    }
}
