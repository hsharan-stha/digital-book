<?php

namespace App\Http\Controllers;


use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index(Request $request)
    {


        $cartList = Cart::with('book')->where("user_id", Auth::user()->id)->get();
        $cartCount = Cart::where("user_id", Auth::user()->id)->count();

        // dd($cartList);

        return view('cart', data: compact("cartList", "cartCount"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        // Check if already exists
        $existing = Cart::where('user_id', Auth::user()->id)
            ->where('book_id', $request->book_id)
            ->first();

        $bookInfo = Book::where('id', $request->book_id)->first();


        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Already in cart.',
                'bookInfo' => $bookInfo,
                'cartCount' => Cart::where('user_id', Auth::user()->id)->count()
            ]);
        }

        $data = Cart::create([
            'user_id' => Auth::user()->id,
            'book_id' => $request->book_id,
            'quantity' => $request->quantity ?? 1
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Added to cart successfully.',
            'data' => $data,
            'bookInfo' => $bookInfo,
            'cartCount' => Cart::where('user_id', Auth::user()->id)->count()
        ]);
    }



    public function updateQuantity(Request $request)
    {
        $request->validate([
            'book_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Cart::where('user_id', Auth::user()->id)
            ->where('book_id', $request->book_id)
            ->first();

        if ($cart) {
            $cart->quantity = $request->quantity;
            $cart->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Cart item not found'], 404);
    }

    public function deleteCartItem(Request $request)
    {
        $request->validate([
            'book_id' => 'required|integer',
        ]);

        $deleted = Cart::where('user_id', Auth::user()->id)
            ->where('book_id', $request->book_id)
            ->delete();

        $cartCount = Cart::where("user_id", Auth::user()->id)->count();

        if ($deleted) {
            return response()->json(['success' => true, 'cartCount' => $cartCount]);
        }

        return response()->json(['success' => false, 'message' => 'Cart item not found'], 404);
    }

}