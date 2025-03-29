<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Menu;
use Inertia\Inertia;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = Menu::with('category')
            ->orderBy('created_at', 'desc') // ✅ Sort by latest first
            ->paginate(4); // Paginate with 10 items per page
        $categories = MenuCategory::all(); 
    
        return Inertia::render('Menu/Index', [
            'menuItems' => $menuItems,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $categories = MenuCategory::all();
        return Inertia::render('Menu/Create', ['categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        //Log::info($request->all()); // Log request data to check values
    
        // Ensure checkboxes are always included in the request
        // $request->merge([
        //     'is_visible' => $request->has('is_visible'),
        //     'is_available' => $request->has('is_available'),
        //     'is_featured' => $request->has('is_featured'),
        //     'is_chef_special' => $request->has('is_chef_special'),
        // ]);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:menu_categories,id',
            'short_label' => 'nullable|string',
            'side_note' => 'nullable|string',
            'is_visible' => 'boolean',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_chef_special' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        //Log::info($validated); // Log validated results 
    
        $menuItem = Menu::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menuItem->image_path) {
                Storage::disk('public')->delete($menuItem->image_path);
            }
            
            $path = $request->file('image')->store('menu-items', 'public');
            $validated['image_path'] = $path;
        }

        $menuItem->update($validated);
    
        return redirect()->route('menu-items.index')->with('success', 'Menu item updated successfully');
    }


    public function store(Request $request)
    {
        // Debug request data
        Log::info('Menu item request:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:menu_categories,id',
            'short_label' => 'nullable|string',
            'side_note' => 'nullable|string',
            'is_visible' => 'boolean',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_chef_special' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-items', 'public');
            $validated['image_path'] = $path;
        }

        // Debug validated data before insertion
        Log::info('Validated menu item data:', $validated);

        Menu::create($validated);

        return redirect()->route('menu-items.index')->with('success', 'Menu item created successfully');
    }


    public function edit($id)
    {
        $menuItem = Menu::findOrFail($id);
        $categories = MenuCategory::all();
        
        return Inertia::render('Menu/Edit', [
            'menuItem' => $menuItem,
            'categories' => $categories
        ]);
    }

    

    public function destroy($id)
    {
        $menuItem = Menu::findOrFail($id);
        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted successfully');
    }
}
