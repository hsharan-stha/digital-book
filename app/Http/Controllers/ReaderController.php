<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PurchaseDetail;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


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

        $pages = Page::where("book_id", $book_id)->orderBy("pageno", "asc")
            ->get();


        if ($pages->isEmpty()) {
            abort(404, 'No pages found for this book.');
        }

        $userId = Auth::id();
        $sessionKey = "session_{$userId}_{$book_id}";

        // Fetch from DB
        $record = DB::table('reader-sessions')
            ->where('session_key', $sessionKey)
            ->first();

        // Default session data if not found
        $sessionData = [
            'bookId' => $book_id,
            'currentPage' => 1,
            'bookmarks' => [],
        ];

        if ($record) {
            $decoded = json_decode($record->session_data, true);
            if (is_array($decoded)) {
                $sessionData = array_merge($sessionData, $decoded);
            }
        }

        return view('reader', compact('pages', "book_id", "sessionData"));
    }


    public function saveSession(Request $request)
    {
        $request->validate([
            'bookId' => 'required|integer',
            'currentPage' => 'required|integer',
            'bookmarks' => 'nullable|array',
        ]);

        $userId = Auth::id();
        $bookId = $request->bookId;

        $sessionKey = "session_{$userId}_{$bookId}";

        $sessionData = [
            'bookId' => $bookId,
            'currentPage' => $request->currentPage,
            'bookmarks' => $request->bookmarks ?? [],
        ];

        DB::table('reader-sessions')->updateOrInsert(
            ['session_key' => $sessionKey],
            ['session_data' => json_encode($sessionData)]
        );

        return response()->json([
            'message' => 'Session saved successfully',
        ]);
    }
}