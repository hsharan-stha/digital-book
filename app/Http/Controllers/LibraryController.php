<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use Illuminate\Http\Request;

class LibraryController extends Controller
{

    public function index(Request $request)
    {


        $cartCount = Cart::where("user_id", 1)->count();

        // dd($cartList);

        return view('library', compact("cartCount"));
    }

}