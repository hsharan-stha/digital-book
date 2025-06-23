<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
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
        if (Auth::check()) {
            $cartCount = Cart::where("user_id", Auth::user()->id)->count();
        }

        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }


        return view('home', compact('categories', "filteredData", "categoryList", "cartCount"));
    }

    public function details(Request $request, $book_id)
    {
        $bookDetails = Book::with([
            'pages' => function ($query) {
                $query->orderBy('id')  // or 'page_number', if applicable
                    ->skip(1)        // Skip the first page (index 0)
                    ->take(4);       // Load pages 2 to 5 (4 pages)
            },
            'category'
        ])->where('id', $book_id)->first();
        return view('details', compact('bookDetails'));
    }

}