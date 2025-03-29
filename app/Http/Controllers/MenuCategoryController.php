<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
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
        ]);

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
        ]);

        $menuCategory->update($validated);

        return redirect()->route('menu-category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(MenuCategory $menuCategory)
    {
        $menuCategory->delete();
        return redirect()->route('menu-category.index')->with('success', 'Category deleted successfully.');
    }
}
