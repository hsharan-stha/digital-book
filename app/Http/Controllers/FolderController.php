<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    // Create new folder
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exists = Folder::where('user_id', Auth::id())
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Folder already exists with this name.',
            ], 409);
        }

        $folder = Folder::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'name' => $folder->name
        ]);
    }

    // Rename existing folder
    public function rename(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:255|unique:folders,name,NULL,id,user_id,' . Auth::id(),
        ]);

        $folder = Folder::where('name', $request->old_name)
            ->where('user_id', Auth::id())
            ->first();

        if (!$folder) {
            return response()->json(['error' => 'Folder not found'], 404);
        }

        $folder->update(['name' => $request->new_name]);

        return response()->json(['success' => true]);
    }

    // Delete folder and unassign books from it
    public function destroy(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        $folder = Folder::where('name', $request->name)
            ->where('user_id', Auth::id())
            ->first();

        if (!$folder) {
            return response()->json(['error' => 'Folder not found'], 404);
        }

        // Unassign books in this folder
        PurchaseDetail::where('folder_id', $folder->id)
            ->where('user_id', Auth::id())
            ->update(['folder_id' => null]);

        $folder->delete();

        return response()->json(['success' => true]);
    }

    // Move a book to a folder or unassign it (called via AJAX from drag/drop)
    public function moveBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|integer|exists:purchase_details,book_id',
            'folder_name' => 'nullable|string',
        ]);

        $bookRecords = PurchaseDetail::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->get();

        if ($bookRecords->isEmpty()) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $folderId = null;
        $newSort = null;
        if ($request->folder_name) {
            $folder = Folder::where('name', $request->folder_name)
                ->where('user_id', Auth::id())
                ->first();

            if (!$folder) {
                return response()->json(['error' => 'Target folder not found'], 404);
            }

            $folderId = $folder->id;

            // Find current max sort value in that folder
            $maxSort = PurchaseDetail::where('folder_id', $folderId)
                ->where('user_id', Auth::id())
                ->max('order');

            $newSort = $maxSort ? $maxSort + 1 : 1;
        }



        // Update folder_id for all records
        PurchaseDetail::where('book_id', $request->book_id)
            ->where('user_id', Auth::id())
            ->update(['folder_id' => $folderId, "order" => $newSort]);

        return response()->json(['success' => true]);
    }

    public function sortBook(Request $request)
    {
        $request->validate([
            'source_id' => 'required|integer',
            'target_id' => 'required|integer',
            'folder_name' => 'nullable|string',
        ]);

        $userId = Auth::id();

        $sourceRows = PurchaseDetail::where('book_id', $request->source_id)
            ->where('user_id', $userId)
            ->get();

        $targetRows = PurchaseDetail::where('book_id', $request->target_id)
            ->where('user_id', $userId)
            ->get();

        if ($sourceRows->isEmpty() || $targetRows->isEmpty()) {
            return response()->json(['error' => 'One or both books not found.'], 404);
        }

        // Check folder consistency
        $sourceFolderId = $sourceRows->first()->folder_id;
        $targetFolderId = $targetRows->first()->folder_id;

        if ($sourceFolderId !== $targetFolderId) {
            return response()->json(['error' => 'Books must be in the same folder to sort.'], 422);
        }

        // Swap order values
        $sourceOrder = $sourceRows->first()->order;
        $targetOrder = $targetRows->first()->order;

        PurchaseDetail::where('book_id', $request->source_id)
            ->where('user_id', $userId)
            ->update(['order' => $targetOrder]);

        PurchaseDetail::where('book_id', $request->target_id)
            ->where('user_id', $userId)
            ->update(['order' => $sourceOrder]);

        return response()->json(['success' => true]);
    }


}
