<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use InertiaToast\Facades\Toast;

class OrderController extends Controller
{
    public function history(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', $user->id)
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');

        return Inertia::render('user/OrderHistory', [
            'orders' => $orders,
            'cartCount' => $cartCount,
        ]);
    }
    public function checkout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
            $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');

            return Inertia::render('user/Checkout', [
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
            ]);
        }
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'order_status' => 'Pending',
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price,
            ]);
        }

        CartItem::where('user_id', $user->id)->delete();

        Toast::success('Order placed successfully!');
        return redirect()->route('home');
    }
}
