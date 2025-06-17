<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:books,name',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Save file
        $bookName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->name); // name
        
        $path = public_path('images/'.$bookName.'/cover');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path2 = public_path('images/'.$bookName.'/pages');
        if (!file_exists($path2)) {
            mkdir($path2, 0755, true);
        }
        $destinationPath = public_path('images/'.$bookName); // public/images/book_cover -folder
        
        if ($request->hasFile('image')) {           
            $extension = $request->file('image')->getClientOriginalExtension(); // jpg, png
            $filename = $bookName . '.' . $extension;
            
            // move file
            $request->file('image')->move($destinationPath.'/cover', $filename);
            $imagePath = 'images/'.$bookName.'/cover/' . $filename;
        }        

        // create model
        $book = Book::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'images' => $imagePath,
            'user_id' => auth()->id(),
            'company_id' => 1,
        ]);

        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $pageImage) {

                $originalName = $pageImage->getClientOriginalName();
                $baseName = pathinfo($originalName, PATHINFO_FILENAME); // natija: "1"
                $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
                $pageImage->move($destinationPath . '/pages', $filename);
                $imagePath = 'images/' . $bookName . '/pages/' . $filename;

                $book->pages()->create([
                    'page_image' => $imagePath,
                    'title' => '',
                    'pageno' => $baseName,
                ]);
            }
        }
        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:books,name,' . $book->id,
            'description' => 'required|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Checking image
        if ($request->hasFile('image')) {
            // Eski rasmni o‘chiramiz (agar mavjud bo‘lsa)
            if ($book->image && file_exists(public_path($book->image))) {
                unlink(public_path($book->image));
            }

            $bookName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->name);
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = $bookName . '.' . $extension;

            $destinationPath = public_path('images/book_cover');
            $request->file('image')->move($destinationPath, $filename);

            $book->image = 'images/book_cover/' . $filename;
        }

        // update 
        $book->name = $request->name;
        $book->description = $request->description;
        $book->category_id = $request->category_id;
        $book->user_id = auth()->id();
        $book->save();

        
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted.');
    }

}    