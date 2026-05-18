<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function tenantId(): int
    {
        return Auth::user()->tenant_id;
    }

    public function index()
    {
        $categories = Category::where('tenant_id', $this->tenantId())
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('owner.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        Category::create([
            'tenant_id' => $this->tenantId(),
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'icon' => $request->icon,
            'color' => $request->color ?? '#6366f1',
            'is_active' => true,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        abort_if($category->tenant_id !== $this->tenantId(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'color' => $request->color,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        abort_if($category->tenant_id !== $this->tenantId(), 403);

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
