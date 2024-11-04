<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
class SearchController extends Controller
{
    public function searchUser(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $request->input('query');
        $users = User::where('name', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%')->orWhere('block', 'like', '%' . $query . '%')->get();
        return $users;
        
    }
     public function searchProduct(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $request->input('query');
        $products = Product::where('title', 'like', '%' . $query . '%')->orWhereHas('category_id', function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%');
            })
            ->get();
        return $products;
        
    }
     public function searchOrder(Request $request)
    {
        
        
    }
}
