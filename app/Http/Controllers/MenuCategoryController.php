<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MenuCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage menu');
    }

    public function index()
    {
        return Inertia::render('MenuCategory/Index', [
            'categories' => MenuCategory::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('MenuCategory/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menu_categories,name',
            'description' => 'nullable|string',
            'display_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('display_image')) {
            $validated['display_image'] = $request->file('display_image')->store('menu-categories', 'public');
        }

        MenuCategory::create($validated);

        return redirect()->route('menu-category.index')->with('success', 'Category added successfully.');
    }

    public function edit(MenuCategory $menuCategory)
    {
        return Inertia::render('MenuCategory/Edit', [
            'category' => $menuCategory
        ]);
    }

    public function update(Request $request, MenuCategory $menuCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menu_categories,name,' . $menuCategory->id,
            'description' => 'nullable|string',
            'display_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('display_image')) {
            // Delete old image if exists
            if ($menuCategory->display_image) {
                Storage::disk('public')->delete($menuCategory->display_image);
            }
            
            $validated['display_image'] = $request->file('display_image')->store('menu-categories', 'public');
        }

        $menuCategory->update($validated);

        return redirect()->route('menu-category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(MenuCategory $menuCategory)
    {
        // Delete image if exists
        if ($menuCategory->display_image) {
            Storage::disk('public')->delete($menuCategory->display_image);
        }
        
        $menuCategory->delete();
        return redirect()->route('menu-category.index')->with('success', 'Category deleted successfully.');
    }
}
