<?php

namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Folder;
use App\Models\PurchaseDetail;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{

    public function index(Request $request)
    {


        $cartCount = Cart::where("user_id", operator: Auth::user()->id)->count();
        $loggedInDevices = DB::table(table: 'sessions')->where("user_id", Auth::user()->id)->count();

        $purchasesList = PurchaseDetail::with(['book', "folder"])
            ->whereHas('purchase', function ($query) {
                $query->where('is_paid', 1);
            })
            ->where('user_id', Auth::user()->id)
            ->orderByRaw('COALESCE(folder_id, 0) ASC') // group nulls first
            ->orderBy('order', 'asc')
            ->get()
            ->unique('book_id')
            ->map(function ($purchase) {
                return [
                    'id' => $purchase->book->id,
                    'src' => $purchase->book->images,
                    'name' => $purchase->book->name,
                    'folder' => $purchase->folder ? $purchase->folder->name : null,
                    "order" => $purchase->order,
                ];
            })
            ->values(); // optional: reset the keys

        $folders = Folder::where('user_id', Auth::id())->get();


        return view('library', compact("cartCount", "purchasesList", "folders", "loggedInDevices"));
    }

    public function getBooksByFolder($name)
    {
        $userId = Auth::id();

        // Find folder by name and user
        $folder = Folder::where('name', $name)
            ->where('user_id', $userId)
            ->first();

        if (!$folder) {
            return response()->json(['error' => 'Folder not found'], 404);
        }

        // Get books inside the folder sorted by 'order'
        $books = PurchaseDetail::with('book')
            ->where('folder_id', $folder->id)
            ->where('user_id', $userId)
            ->whereHas('purchase', function ($q) {
                $q->where('is_paid', 1);
            })
            ->orderByRaw('COALESCE(folder_id, 0) ASC') // group nulls first
            ->orderBy('order', 'asc')
            ->get()
            ->unique('book_id')
            ->map(function ($purchase) {
                return [
                    'id' => $purchase->book->id,
                    'src' => $purchase->book->images,
                    'name' => $purchase->book->name,
                    'folder' => $purchase->folder->name,
                    "order" => $purchase->order,
                ];
            })
            ->values();

        return response()->json($books);
    }

}