<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{

    public function index(Request $request)
    {


        $cartCount = Cart::where("user_id", operator: Auth::user()->id)->count();

        // dd($cartList);

        return view('library', compact("cartCount"));
    }

}