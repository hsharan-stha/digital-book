<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function index(Book $book)
    {
        
        $pages = $book->pages()->orderBy('pageno')->get();
        return view('pages.index', compact('book','pages'));
    }
    

    public function destroy(Page $page)
    {

        $filePath = public_path($page->page_image);;
      
            if (File::exists($filePath)) {
                 File::delete($filePath);
            }

        $page->delete();

        return redirect()->route('books.pages.index', $page->book_id)->with('success', 'Page deleted successfully.');
    }

     public function destroyAll(Book $book)
    {

        // build book folder path
    $folderPath = public_path('images/' . $book->name."/pages");

    // dd(File::exists($folderPath));

    // delete entire folder
    if (File::exists($folderPath)) {
        File::deleteDirectory($folderPath);
    }
      
        Page::where("book_id",$book->id)->delete();
        return redirect()->route('books.pages.index', $book->id)->with('success', 'All Pages deleted successfully.');
    }

    
     public function addPageAfter(Book $book,Request $request)
    {
    //   dd($book,$request);
       Page::where('book_id', $book->id)->where('pageno', '>', $request->addPageAfter)->increment('pageno', 1);
        return redirect()->route('books.pages.index', $book->id)->with('success', 'Added Pages Successfully After page number ='.  $request->addPageAfter);
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
                // $filename =$originalName;

                $pageImage->move($destinationPath . '/pages', $filename);
                $imagePath = 'images/' . $book->name . '/pages/' . $filename;

                $book->pages()->create([
                    'page_image' => $imagePath,
                    'title' => '',
                    'pageno' => $baseName,
                ]);
            }
        }
        
        return redirect()->route('books.pages.index',$request->book_id)->with('success', 'Pages inserted successfully.');
    }
}    