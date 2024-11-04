<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;



class ProductController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
        
        new Middleware("auth:sanctum", except: ['index', 'show']),
             new Middleware('role:user', only: ['store', 'update', 'destroy']),
    ];
}

  
    public function index(Request $request)
    {
         $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $request->input('query');
        $products = Product::where('title', 'like', '%' . $query . '%')->paginate(5);
        return new ProductCollection($products);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'description'=>'required',
            'price'=>'required',
            'quantity'=>'required',
            'image'=>'array',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id'=>'required'
        ]);
        // $imageName = time().'.'.$data["image"]->extension();
        // $data["image"]->move(public_path('images'), $imageName);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        // $data["image"] = 'images/'.$imageName;
        $images = [];
        if ($request->hasfile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('photos', 'public'); // Save photo and get path
                $images[] = $path; // Add to the photos array
            }
        }
        $product = Product::create($data);
        $product->image = $images;
        $product->save();
        return new ProductResource($product);
    }

     public function show(string $id)
    {
        $product = Product::find($id);
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
    $request->validate([
        'title' => 'required|string',
        'description' => 'required',
        'price' => 'required|numeric', // Consider validating as numeric
        'quantity' => 'required|integer', // Validate as integer
        // 'image' => 'array', // Allow for multiple images
        'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        'index' => 'required|integer|min:0', // Assuming old_image is an index or ID
        'category_id' => 'required|exists:categories,id' // Ensure category exists
    ]);

    $indexToUpdate = $request->input('index');
    $images = $product->image;
    if($request->old_image !== null && $request->hasfile('image'))
    {
        Storage::disk('public')->delete($product->image[$request->old_image]);
        $path = $request->file('image')->store('photos', 'public');
        // echo $path;
        $images[$indexToUpdate] = $path;
         $product->image = $images;
    }elseif ($request->old_image) {
        if (isset($product->image[$request->old_image])) {
            Storage::disk('public')->delete($product->image[$request->old_image]);
        }
    }


    $product->title = $request->title;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->category_id = $request->category_id;
    $product->updated_by = $request->user()->id;


    // Save the product
    $product->save();

    return response()->json([
        "success" => "Product updated"
    ]);
}

      public function destroy(string $id)
    {
        $product = Product::find($id);
        foreach($product->image as $image)
        {
            Storage::disk('public')->delete($image);
        }
        
        $product->delete();
       return response()->json([
            "success"=>"product deleted"
        ]);
    }


}
