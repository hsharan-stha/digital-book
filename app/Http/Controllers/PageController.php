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
}    