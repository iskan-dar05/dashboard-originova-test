<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
        
       new Middleware("auth:sanctum", except: ['index', 'show']),
             new Middleware('role:user', only: ['store', 'update', 'destroy']),
            new Middleware('permission:edit', ['only' => ['edit','update']]),
            new Middleware('permission:create', ['only' => ['store']]),
];
    }
    
   public function index(Request $request)
    {  
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $request->input('query');
        $products = Product::where('category_id', $query)->paginate(5);
        return new ProductCollection($products);
    }

    
    // Create category

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return new CategoryResource($category);
    }

    // GET category

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    // UPDATE category

    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return response()->json([
            "success"=>"category updated"
        ]);
    }

    // DELETE category

    public function destroy(Category $category)
    {
        $products = $category->products;
        foreach($products as $product)
        {
            DB::table('order_product')->where('product_id', $product->id)->delete();
            foreach($product->image as $image){
            Storage::disk('public')->delete($image);
            }
        }
        $category->products()->delete();
        $category->delete();
        return response()->json([
            "success"=>"category deleted"
        ]);
    }
}
