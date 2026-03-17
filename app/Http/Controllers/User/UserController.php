<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'Available')->limit(6)->get();

        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        }

        return Inertia::render('user/Index', [
            'products' => $products,
            'cartCount' => $cartCount,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
        ]);
    }

    public function productView(Request $request)
    {
        $query = Product::where('status', 'Available');

        if ($request->has('type') && $request->type !== '' && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->get();

        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
        }

        return Inertia::render('user/ProductList', [
            'products' => $products,
            'cartCount' => $cartCount,
            'filters' => [
                'type' => $request->type ?? '',
                'sort' => $request->sort ?? 'name_asc',
                'search' => $request->search ?? '',
            ],
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
        ]);
    }
}
