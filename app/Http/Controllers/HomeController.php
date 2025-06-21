<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display the home page with menus
     */
    public function index()
    {
        // Get all menus from database
        $menus = Menu::all();
        
        // Process image paths for each menu
        $menus = $menus->map(function($menu) {
            $menu->image_url = $this->getImageUrl($menu->image);
            return $menu;
        });
        
        // Group menus by category for easier display
        $menusByCategory = [
            'foods' => $menus->where('category', 'foods'),
            'drinks' => $menus->where('category', 'drinks'),
            'snacks' => $menus->where('category', 'snacks')
        ];
        
        return view('home', compact('menus', 'menusByCategory'));
    }

    /**
     * Search menus based on query
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->route('home');
        }
        
        $menus = Menu::where('menu_name', 'LIKE', '%' . $query . '%')
                    ->orWhere('description', 'LIKE', '%' . $query . '%')
                    ->get();
        
        // Process image paths for each menu
        $menus = $menus->map(function($menu) {
            $menu->image_url = $this->getImageUrl($menu->image);
            return $menu;
        });
        
        $menusByCategory = [
            'foods' => $menus->where('category', 'foods'),
            'drinks' => $menus->where('category', 'drinks'),
            'snacks' => $menus->where('category', 'snacks')
        ];
        
        return view('home', compact('menus', 'menusByCategory', 'query'));
    }

    /**
     * Get menus by category
     */
    public function getByCategory($category)
    {
        $validCategories = ['foods', 'drinks', 'snacks'];
        
        if (!in_array($category, $validCategories)) {
            return redirect()->route('home');
        }
        
        $menus = Menu::where('category', $category)->get();
        
        // Process image paths for each menu
        $menus = $menus->map(function($menu) {
            $menu->image_url = $this->getImageUrl($menu->image);
            return $menu;
        });
        
        $menusByCategory = [
            'foods' => collect(),
            'drinks' => collect(),
            'snacks' => collect()
        ];
        $menusByCategory[$category] = $menus;
        
        return view('home', compact('menus', 'menusByCategory', 'category'));
    }
    
    /**
     * Get proper image URL
     */
    private function getImageUrl($imagePath)
    {
        // If image path is null or empty, return default image
        if (empty($imagePath)) {
            return asset('Assets/default-food.png');
        }
        
        // If it's already a URL, return as is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }
        
        // Remove leading slash if exists
        $imagePath = ltrim($imagePath, '/');
        
        // Check if file exists in public directory
        $publicPath = public_path($imagePath);
        if (file_exists($publicPath)) {
            return asset($imagePath);
        }
        
        // Check if file exists in storage
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::url($imagePath);
        }
        
        // Try different common paths
        $commonPaths = [
            'Assets/' . $imagePath,
            'public/' . $imagePath,
            'menu-images/' . $imagePath,
            'images/' . $imagePath,
            'Assets/' . $imagePath,
            'storage/menu-images/' . $imagePath,
            'storage/app/public/images/' . $imagePath
        ];
        
        foreach ($commonPaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }
        
        // Return default image if nothing found
        return asset('Assets/default-food.png');
    }
}