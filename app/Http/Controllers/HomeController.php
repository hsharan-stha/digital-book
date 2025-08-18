<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('book_name');

        // Optional filter for single category
        $categoryId = $request->input('category_id');

        $categoriesQuery = Category::with([
            'books' => function ($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                }
            }
        ]);

        if ($categoryId) {
            $categoriesQuery->where('id', $categoryId);
        }

        $categories = $categoriesQuery->get();


        $filteredData = $request->input();

        $categoryList = Category::get();


        $cartCount = 0;
        $loggedInDevices = 0;
        if (Auth::check()) {
            $cartCount = Cart::where("user_id", Auth::user()->id)->count();
            $loggedInDevices = DB::table('sessions')->where("user_id", Auth::user()->id)->count();

            if(Auth::user()->role_id===1){
                return redirect()->route('dashboard');
            }
        }

        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }


        return view('home', compact('categories', "filteredData", "categoryList", "cartCount", "loggedInDevices"));
    }

    public function details(Request $request, $book_id)
    {
        $bookDetails = Book::with([
            'pages' => function ($query) {
                $query->orderBy('pageno')  // or 'page_number', if applicable
                    ->skip(0)        // Skip the first page (index 0)
                    ->take(9);       // Load pages 2 to 5 (4 pages)
            },
            'category'
        ])->where('id', $book_id)->first();
        return view('details', compact('bookDetails'));
    }

}