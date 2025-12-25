<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Page;
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
                 $query->orderBy('created_at');
            }
        ])->orderby("created_at");

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

            if (Auth::user()->role_id === 1) {
                return redirect()->route('dashboard');
            }
        }

        // if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
        //     return redirect()->route('verification.notice');
        // }


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
        // Defaults
           
            $enableScale    = false;

            // Find the bracket section: [ ... ]
            if (preg_match('/\[(.*?)\]/', $bookDetails->description, $block)) {
            
                $content = $block[1];
             
                // Extract enableScale
                if (preg_match('/enableScale\s*=\s*(true|false)/i', $content, $m)) {
                    $enableScale = strtolower($m[1]) === 'true';
                }
            }

            

        return view('details', compact('bookDetails',"enableScale"));
    }

    public function readSample(Request $request, $book_id)
    {
         $bookDetails = Book::where('id', $book_id)->value("description");
        
            $facepages      = 0;
           
            // Find the bracket section: [ ... ]
            if (preg_match('/\[(.*?)\]/', $bookDetails, $block)) {

                $content = $block[1]; // inside: facepages=2,lastpagenumber=183,enableScale=true

                // Extract facepages
                if (preg_match('/facepages\s*=\s*(\d+)/i', $content, $m)) {
                    $facepages = (int)$m[1];
                }

            }

        $pages = Page::where("book_id", $book_id)->orderBy("pageno", "asc")
            ->skip(0)->take(20+ $facepages)->get();


        if ($pages->isEmpty()) {
            abort(404, 'No pages found for this book.');
        }

        // Default session data if not found
        $sessionData = [
            'bookId' => $book_id,
            'currentPage' => 1,
            'bookmarks' => [],
        ];

            // Defaults
            $facepages      = 0;
            $lastPageNumber = count($pages);
            $enableScale    = false;

            // Find the bracket section: [ ... ]
            if (preg_match('/\[(.*?)\]/', $bookDetails, $block)) {

                $content = $block[1]; // inside: facepages=2,lastpagenumber=183,enableScale=true

                // Extract facepages
                if (preg_match('/facepages\s*=\s*(\d+)/i', $content, $m)) {
                    $facepages = (int)$m[1];
                }

                // Extract lastpagenumber
                if (preg_match('/lastpagenumber\s*=\s*(\d+)/i', $content, $m)) {
                    $lastPageNumber = (int)$m[1];
                }

                // Extract enableScale
                if (preg_match('/enableScale\s*=\s*(true|false)/i', $content, $m)) {
                    $enableScale = strtolower($m[1]) === 'true';
                }
            }

            $pageNumberDetails = [$facepages, $lastPageNumber,$enableScale];

       

        return view('read-sample', compact('pages', "book_id", "sessionData","pageNumberDetails"));
    }

}