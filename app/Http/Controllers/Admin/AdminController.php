<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(){
        $totalUsers = User::where('role', 'user')->count();
        
        $totalProductsSold = OrderItem::sum('quantity');
        
        $latestMonth = now()->subMonth()->startOfMonth();
        $totalSalesLatestMonth = DB::table('orders')
            ->where('created_at', '>=', $latestMonth)
            ->sum('total_price');
        
        $productsSold = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product?->name ?? 'Unknown Product',
                    'quantity_sold' => (int) $item->total_sold,
                ];
            });

        $salesByType = OrderItem::select('products.type', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->type ?? 'Unknown',
                    'quantity_sold' => (int) $item->total_sold,
                ];
            });

        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'totalProductsSold' => $totalProductsSold,
                'totalSalesLatestMonth' => $totalSalesLatestMonth,
                'productsSold' => $productsSold,
                'salesByType' => $salesByType,
            ]
        ]);
    }
}
