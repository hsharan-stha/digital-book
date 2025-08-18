<?php

namespace App\Http\Controllers;


use App\Models\Book;
use App\Models\Cart;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function indexWeb(Request $request)
    {


        $cartList = Cart::with('book')->where("user_id", Auth::user()->id)->get();
        $cartCount = Cart::where("user_id", Auth::user()->id)->count();
        $loggedInDevices = DB::table(table: 'sessions')->where("user_id", Auth::user()->id)->count();

        $totalPrice = $cartList->sum(function ($cartItem) {
            return $cartItem->book->price ?? 0;
        });
        return view('cart', compact('cartList', 'cartCount', "totalPrice","loggedInDevices"));


    }

    public function index(Request $request)
    {


        $cartList = Cart::with('book')->where("user_id", Auth::user()->id)->get();
        $cartCount = Cart::where("user_id", Auth::user()->id)->count();
        $loggedInDevices = DB::table(table: 'sessions')->where("user_id", Auth::user()->id)->count();


        return response()->json([
            'cartList' => $cartList,
            'cartCount' => $cartCount,
            'loggedInDevices' => $loggedInDevices,
        ]);
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


    public function storeBulk(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:books,id',
            'items.*.qty' => 'nullable|integer|min:1',
        ]);

        $userId = Auth::user()->id;
        $addedItems = [];
        $skippedItems = [];

        foreach ($request->items as $item) {
            $bookId = $item['id'];
            $quantity = $item['qty'] ?? 1;

            // Check if book already in cart
            $existing = Cart::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->first();

            $bookInfo = Book::find($bookId);

            if ($existing) {
                $skippedItems[] = [
                    'book_id' => $bookId,
                    'message' => 'Already in cart.',
                    'bookInfo' => $bookInfo,
                ];
                continue;
            }

            $cartItem = Cart::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'quantity' => $quantity
            ]);

            $addedItems[] = [
                'book_id' => $bookId,
                'data' => $cartItem,
                'bookInfo' => $bookInfo,
                'message' => 'Added successfully.',
            ];
        }

        $cartCount = Cart::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Bulk add-to-cart operation completed.',
            'added' => $addedItems,
            'skipped' => $skippedItems,
            'cartCount' => $cartCount,
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