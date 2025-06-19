<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Request;


class ReaderController extends Controller
{
    public function index(Request $request, $book_id)
    {
        $pages = Page::where("book_id", $book_id)->get();
        

        if ($pages->isEmpty()) {
            abort(404, 'No pages found for this book.');
        }

        return view('reader', compact('pages',"book_id"));
    }
}