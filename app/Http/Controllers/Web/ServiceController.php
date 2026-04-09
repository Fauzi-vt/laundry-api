<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /** Simpan layanan baru */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:services,name',
            'price' => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
        ]);

        Service::create($request->only('name', 'price', 'unit'));

        return redirect()->route('dashboard', ['tab_master' => '1'])
            ->with('success', "Layanan '{$request->name}' berhasil ditambahkan.");
    }

    /** Update layanan */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'name'  => "required|string|max:255|unique:services,name,{$id}",
            'price' => 'required|numeric|min:0',
            'unit'  => 'required|string|max:50',
        ]);

        $service->update($request->only('name', 'price', 'unit'));

        return redirect()->route('dashboard', ['tab_master' => '1'])
            ->with('success', "Layanan '{$service->name}' berhasil diperbarui.");
    }

    /** Hapus layanan */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $name = $service->name;
        $service->delete();

        return redirect()->route('dashboard', ['tab_master' => '1'])
            ->with('success', "Layanan '{$name}' berhasil dihapus.");
    }
}
