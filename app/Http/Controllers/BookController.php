<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Page;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $user_role = auth()->user()->role_id;
        if($user_role==1) {
            $books = Book::all();
        }
        else {
            $companyId = auth()->user()->company_id;
            $books = Book::where('company_id', $companyId)->get();
        }
        
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        $companies = Company::all();
        return view('books.create', compact('categories','companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:books,name',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
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
            'price' => $request->price ?? null,
            'images' => $imagePath,
            'user_id' => auth()->id(),
            'company_id' => $request->company_id ?? auth()->user()->company_id,
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
        $companies = Company::all();
        return view('books.edit', compact('book', 'categories', 'companies'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:books,name,' . $book->id,
            'description' => 'required|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'nullable|numeric|min:0',
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
        $book->price = $request->price ?? null;
        $book->user_id = auth()->id();
        $book->company_id = $request->company_id ?? auth()->user()->company_id;
        $book->save();

        
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted.');
    }

}    