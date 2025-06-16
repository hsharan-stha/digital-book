<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

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
        

        $filteredData=$request->input();

        $categoryList=Category::get();

        // dd($filteredData);

        return view('home', compact('categories',"filteredData","categoryList"));
    }

}