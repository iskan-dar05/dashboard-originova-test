<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware("auth:sanctum", except: ['index', 'show']),
             new Middleware('role:user', only: ['index', 'update', 'destroy']),
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
        $orders = Order::where('fullname', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%')->paginate(5);
        return new OrderCollection($orders);
    }

    // CREATE order
   
    public function store(Request $request)
    {
        $data = $request->products;
       $ids = array_column($request->products, 'id');
       $products = Product::whereIn('id', $ids)->get();
       $price = 0;
       foreach($products as $product)
       {
            $requestedProduct = collect($data)->firstWhere('id', $product->id);
            if($product->quantity >= $requestedProduct["quantity"])
            {
                $price += $product->price * $requestedProduct['quantity'];
            }else{
                array_filter($data, function($item) use ($requestedProduct){
                    return $item['id'] !== $requestedProduct['id'];
                });
            }
       }
        $order = Order::create([
            'fullname'=> $request->fullname,
            'email'=>$request->email,
            'status'=>'appended',
            'price'=>$price
        ]);
        foreach($data as $product_obj)
        {
            $order->products()->attach([
                $product_obj['id'] => [
                    'quan'=> $product_obj['quantity'],
                ]
                ]
            );

        }
        // return $order->load('products');
        return new OrderResource($order);

   }

    // GET order only admin

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    // Update Order only admin

    public function update(Request $request, Order $order)
    {
        $products = $order->load('products')->products;
        
        if($order->status == 'accepted' && $request->status == 'appended')
        {
            foreach($products as $product)
            {
                $product->quantity += $product->pivot->quan;
                $product->save();
            // echo $product->pivot->quan;
            }
            $order->status = $request->status;
            $order->save();
            return $order->load('products');
        }
        
        else if($order->status == 'appended' && $request->status == 'accepted'){
            // $products = $order->load('products')->products;
            foreach($products as $product)
            {
                $product->quantity -= $product->pivot->quan;
                $product->save();
            // echo $product->pivot->quan;
            }
            $order->status = $request->status;
            $order->save();
            // return $order->load('products');
            return new OrderResource($order);

        }
        
        else {
            return $order->update($request->all());
        }
    }

    
    // DELETE order only admin

    public function destroy(Order $order)
    {
        // $products = $order->load('products')->products;
        // foreach($products as $product)
        // {
        //         $product->quantity += $product->pivot->quan;
        //         $product->save();
        // }
        $order->delete();
    }
}
