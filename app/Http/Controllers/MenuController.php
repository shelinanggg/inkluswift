<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Menampilkan halaman menu management
     */
    public function index()
    {
        return view('menu');
    }

    /**
     * Mendapatkan daftar menu dengan pagination
     */
    public function getMenus(Request $request)
    {
        $search = $request->search ?? '';
        $category = $request->category ?? '';
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $query = Menu::query();

        // Filter berdasarkan search
        if (!empty($search)) {
            $query->where('menu_id', 'like', "%{$search}%")
                  ->orWhere('menu_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%");
        }

        // Filter berdasarkan category
        if (!empty($category)) {
            $query->where('category', $category);
        }

        // Total data
        $totalItems = $query->count();

        // Get data dengan pagination
        $menus = $query->orderBy('menu_name', 'asc')
                      ->skip(($page - 1) * $perPage)
                      ->take($perPage)
                      ->get();

        return response()->json([
            'menus' => $menus,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Menyimpan menu baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'menu_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'ingredients' => 'nullable|string|max:500',
            'storage' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate menu_id
        $lastMenu = Menu::orderBy('menu_id', 'desc')->first();
        $menuIdNumber = $lastMenu ? (int)substr($lastMenu->menu_id, 1) + 1 : 1;
        $menuId = 'M' . str_pad($menuIdNumber, 4, '0', STR_PAD_LEFT);

        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-images', 'public');
        }

        // Buat menu baru
        $menu = Menu::create([
            'menu_id' => $menuId,
            'menu_name' => $request->menu_name,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'discount' => $request->discount ?? 0,
            'category' => $request->category,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'storage' => $request->storage,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Menu created successfully',
            'menu' => $menu
        ], 201);
    }

    /**
     * Mendapatkan detail menu
     */
    public function show($id)
    {
        $menu = Menu::where('menu_id', $id)->first();
        
        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        return response()->json(['menu' => $menu]);
    }

    /**
     * Update menu
     */
    public function update(Request $request, $id)
    {
        // Cari menu
        $menu = Menu::where('menu_id', $id)->first();
        
        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'menu_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'ingredients' => 'nullable|string|max:500',
            'storage' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Data untuk update
        $menuData = [
            'menu_name' => $request->menu_name,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'discount' => $request->discount ?? 0,
            'category' => $request->category,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'storage' => $request->storage,
        ];

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            
            // Upload gambar baru
            $menuData['image'] = $request->file('image')->store('menu-images', 'public');
        }

        // Update menu
        $menu->update($menuData);

        return response()->json([
            'message' => 'Menu updated successfully',
            'menu' => $menu
        ]);
    }

    /**
     * Hapus menu
     */
    public function destroy($id)
    {
        $menu = Menu::where('menu_id', $id)->first();
        
        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        // Hapus gambar jika ada
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        // Hapus menu
        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully']);
    }

    /**
     * Mendapatkan daftar kategori unik
     */
    public function getCategories()
    {
        $categories = Menu::select('category')
                         ->distinct()
                         ->whereNotNull('category')
                         ->where('category', '!=', '')
                         ->orderBy('category')
                         ->pluck('category');

        return response()->json(['categories' => $categories]);
    }
}