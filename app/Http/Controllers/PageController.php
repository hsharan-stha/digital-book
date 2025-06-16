<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Book $book)
    {
        
        $pages = $book->pages;
        return view('pages.index', compact('book','pages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'pageno' => 'required|integer',
        ]);

        $page = Page::create($validated);

        return response()->json([
            'success' => true,
            'data' => $page,
            'message' => 'Page created successfully.',
        ]);
    }
}    