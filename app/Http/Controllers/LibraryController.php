<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Folder;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{

    public function index(Request $request)
    {


        $cartCount = Cart::where("user_id", operator: Auth::user()->id)->count();

        $purchasesList = PurchaseDetail::with(['book', "folder"])
            ->where('user_id', Auth::user()->id)
            ->get()
            ->unique('book_id')
            ->map(function ($purchase) {
                return [
                    'id' => $purchase->book->id,
                    'src' => $purchase->book->images,
                    'name' => $purchase->book->name,
                    'folder' => $purchase->folder ? $purchase->folder->name : null,
                ];
            })
            ->values(); // optional: reset the keys

        $folders = Folder::where('user_id', Auth::id())->get();


        return view('library', compact("cartCount", "purchasesList","folders"));
    }

}