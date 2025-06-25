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
    public function index(Request $request)
    {
        $query = MenuItem::with('category');
        
        // Handle search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('short_label', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('side_note', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        // Handle category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function($categoryQuery) use ($request) {
                $categoryQuery->where('id', $request->category);
            });
        }
        
        // Handle price range filter
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Handle availability filter
        if ($request->has('availability') && $request->availability !== '') {
            $query->where('is_available', $request->availability);
        }
        
        // Handle visibility filter
        if ($request->has('visibility') && $request->visibility !== '') {
            $query->where('is_visible', $request->visibility);
        }
        
        // Handle featured filter
        if ($request->has('featured') && $request->featured !== '') {
            $query->where('is_featured', $request->featured);
        }
        
        // Handle chef special filter
        if ($request->has('chef_special') && $request->chef_special !== '') {
            $query->where('is_chef_special', $request->chef_special);
        }
        
        // Handle sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort column to prevent SQL injection
        $allowedSortColumns = ['name', 'price', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort order
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);
        
        $menuItems = $query->paginate(10);
        $categories = MenuCategory::all();
    
        return Inertia::render('Menu/Index', [
            'menuItems' => $menuItems,
            'categories' => $categories,
            'search' => $request->search ?? '',
            'filters' => [
                'category' => $request->category ?? '',
                'min_price' => $request->min_price ?? '',
                'max_price' => $request->max_price ?? '',
                'availability' => $request->availability ?? '',
                'visibility' => $request->visibility ?? '',
                'featured' => $request->featured ?? '',
                'chef_special' => $request->chef_special ?? '',
            ],
            'sort' => [
                'by' => $sortBy,
                'order' => $sortOrder,
            ],
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
