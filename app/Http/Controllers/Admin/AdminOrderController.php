<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use InertiaToast\Facades\Toast;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy("created_at", "desc")
            ->paginate(10);

        return Inertia::render('admin/orders/index', [
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        if ($order->order_status !== 'Pending') {
            Toast::error('Only pending orders can be updated.');
            return redirect()->back();
        }

        $order->update(['order_status' => 'Success']);

        Toast::success('Order status updated to Success.');
        return redirect()->back();
    }
}
