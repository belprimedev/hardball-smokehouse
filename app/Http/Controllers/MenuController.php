<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\MenuItem;
use Inertia\Inertia;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')
            ->orderBy('created_at', 'desc') // ✅ Sort by latest first
            ->paginate(10); // Paginate with 10 items per page
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
        Log::info('Update request data:', $request->all());
        
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);
        
        Log::info('Validated data:', $validated);
        
        $menuItem = MenuItem::findOrFail($id);
        
        if ($request->hasFile('image')) {
            Log::info('Image file detected');
            
            // Delete old image if exists
            if ($menuItem->image_path) {
                Log::info('Deleting old image:', ['path' => $menuItem->image_path]);
                Storage::disk('public')->delete($menuItem->image_path);
            }
            
            $path = $request->file('image')->store('menu-items', 'public');
            Log::info('New image stored at:', ['path' => $path]);
            $validated['image_path'] = $path;
        }

        Log::info('Updating menu item with data:', $validated);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu-items', 'public');
            $validated['image_path'] = $path;
        }

        // Debug validated data before insertion
        Log::info('Validated menu item data:', $validated);

        MenuItem::create($validated);

        return redirect()->route('menu-items.index')->with('success', 'Menu item created successfully');
    }


    public function edit($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $categories = MenuCategory::all();
        
        return Inertia::render('Menu/Edit', [
            'menuItem' => $menuItem,
            'categories' => $categories
        ]);
    }

    

    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted successfully');
    }

    public function getFeaturedItems()
    {
        $featuredItems = MenuItem::where('is_featured', true)
            ->where('is_visible', true)
            ->where('is_available', true)
            ->with('category')
            ->get();

        return response()->json($featuredItems);
    }

    public function getChefSpecialItems()
    {
        $chefSpecialItems = MenuItem::where('is_chef_special', true)
            ->where('is_visible', true)
            ->where('is_available', true)
            ->with('category')
            ->get();

        return response()->json($chefSpecialItems);
    }

    public function getAllMenuItems()
    {
        $menuItems = MenuItem::where('is_visible', true)
            ->where('is_available', true)
            ->with('category')
            ->get();

        return response()->json($menuItems);
    }
}
