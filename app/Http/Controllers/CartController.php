<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index(Request $request)
    {


        $cartList = Cart::with('book')->where("user_id", 1)->get();
        $cartCount = Cart::where("user_id", 1)->count();

        // dd($cartList);

        return view('cart', compact("cartList", "cartCount"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        // Check if already exists
        $existing = Cart::where('user_id', $request->user_id)
            ->where('book_id', $request->book_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Book already in cart.',
                'cartCount' => Cart::where('user_id', $request->user_id)->count()
            ]);
        }

        $data = Cart::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'quantity' => $request->quantity ?? 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart created successfully.',
            'data' => $data,
            'cartCount' => Cart::where('user_id', $request->user_id)->count()
        ]);
    }

}