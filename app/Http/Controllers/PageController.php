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
        
        $pages = $book->pages()->orderBy('pageno')->get();
        return view('pages.index', compact('book','pages'));
    }
    

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->back()->with('success', 'Page deleted successfully.');
    }

    public function store(Request $request)
    {
        $book = Book::findOrFail($request->book_id);
        $destinationPath = public_path('images/'.$book->name); // public/images/book_name -folder

        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $pageImage) {

                $originalName = $pageImage->getClientOriginalName();
                $baseName = pathinfo($originalName, PATHINFO_FILENAME); // natija: "1"
                $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
                $pageImage->move($destinationPath . '/pages', $filename);
                $imagePath = 'images/' . $book->name . '/pages/' . $filename;

                $book->pages()->create([
                    'page_image' => $imagePath,
                    'title' => '',
                    'pageno' => $baseName,
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Pages inserted successfully.');
    }
}    