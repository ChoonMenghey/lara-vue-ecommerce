<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function view(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
            $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');

            return Inertia::render('user/CartList', [
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
            ]);
        }

        return redirect('/');
    }
    public function store(Request $request, Product $product)
    {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }

            return back()->with('success', 'Product added to cart');
        }
    }
    public function update(Request $request, CartItem $cartItem)
    {
        $quantity = $request->input('quantity');
        
        if ($quantity && $quantity > 0) {
            $cartItem->update(['quantity' => $quantity]);
        }
        
        return back();
    }
    
    public function delete(Request $request, CartItem $cartItem)
    {
        $cartItem->delete();
        
        return back();
    }
}
