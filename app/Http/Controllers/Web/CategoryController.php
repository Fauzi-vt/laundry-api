<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /** Display categories list */
    public function index()
    {
        $user = auth()->user();
        
        $query = Category::withCount('services');

        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.categories', compact('user', 'categories'));
    }

    /** Store a new category */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255|unique:categories,name',
            'description'  => 'nullable|string',
            'icon'         => 'required|string|max:10', // Usually emoji
            'accent_color' => 'required|string|max:50', // e.g. blue, violet, orange, emerald, slate
        ], [
            'name.unique'  => 'Nama kategori sudah digunakan.',
            'name.required' => 'Nama kategori wajib diisi.',
            'icon.required' => 'Ikon kategori wajib dipilih.',
            'accent_color.required' => 'Warna aksen wajib dipilih.',
        ]);

        Category::create($request->only('name', 'description', 'icon', 'accent_color'));

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$request->name}' berhasil ditambahkan.");
    }

    /** Update category */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'         => "required|string|max:255|unique:categories,name,{$id}",
            'description'  => 'nullable|string',
            'icon'         => 'required|string|max:10',
            'accent_color' => 'required|string|max:50',
        ], [
            'name.unique'  => 'Nama kategori sudah digunakan.',
            'name.required' => 'Nama kategori wajib diisi.',
            'icon.required' => 'Ikon kategori wajib dipilih.',
            'accent_color.required' => 'Warna aksen wajib dipilih.',
        ]);

        $category->update($request->only('name', 'description', 'icon', 'accent_color'));

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui.");
    }

    /** Delete category */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if there are services associated with this category
        if ($category->services()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->withErrors(["Tidak dapat menghapus kategori '{$category->name}' karena masih memiliki layanan aktif. Hapus atau pindahkan layanan tersebut terlebih dahulu."]);
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$name}' berhasil dihapus.");
    }
}
