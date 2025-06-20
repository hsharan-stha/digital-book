<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\Auth;
use Request;


class ReaderController extends Controller
{
    public function index(Request $request, $book_id)
    {

        // Check if the user has paid for this book
        $hasPaid = PurchaseDetail::where('book_id', $book_id)
            ->where('user_id', Auth::id())
            ->whereHas('purchase', function ($query) {
                $query->where('is_paid', true);
            })
            ->exists();

        if (!$hasPaid) {
            abort(403, 'Access denied. Payment required to read this book.');
        }

        $pages = Page::where("book_id", $book_id)->get();


        if ($pages->isEmpty()) {
            abort(404, 'No pages found for this book.');
        }

        return view('reader', compact('pages', "book_id"));
    }
}