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
            'name'        => 'required|string|max:255|unique:services,name',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->only('name', 'category_id', 'price', 'unit', 'description');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images/services');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $data['image'] = $filename;
        }

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan '{$request->name}' berhasil ditambahkan.");
    }

    /** Update layanan */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $request->validate([
            'name'        => "required|string|max:255|unique:services,name,{$id}",
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = $request->only('name', 'category_id', 'price', 'unit', 'description');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images/services');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $data['image'] = $filename;

            // Delete old image if exists
            if ($service->image) {
                $oldImagePath = public_path('images/services/' . $service->image);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan '{$service->name}' berhasil diperbarui.");
    }

    /** Hapus layanan */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $name = $service->name;

        // Delete image if exists
        if ($service->image) {
            $oldImagePath = public_path('images/services/' . $service->image);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan '{$name}' berhasil dihapus.");
    }
}
