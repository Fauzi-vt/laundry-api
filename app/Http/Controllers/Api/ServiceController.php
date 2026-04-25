<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('category')->orderBy('name')->get();

        return response()->json([
            'message' => 'success',
            'data'    => $services,
        ]);
    }
}
