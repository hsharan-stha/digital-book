<?php

namespace App\Http\Controllers;

use App\Mail\PurchasePaidConfirmationMail;
use App\Mail\PurchaseSuccessfulMail;
use App\Models\Cart;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class PurchaseController extends Controller
{

    public function index(Request $request)
    {


        $purchase = Purchase::with('details')
            ->where("id", operator: $request->input('purchase_id'))->first();

        // dd($purchase);

        return view('purchase-success', data: compact("purchase"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'books' => 'required|array',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
            'books.*.per_price' => 'required|numeric|min:0',
        ]);


        DB::beginTransaction();

        try {
            // Calculate total amount
            $totalAmount = collect($validated['books'])->sum(function ($item) {
                return $item['quantity'] * $item['per_price'];
            });
            $purchaseDate = now()->format('YmdHis');
            // Create purchase
            $purchase = Purchase::create([
                'total_amount' => $totalAmount,
                'purchase_date' => "-",
                'item_count' => count($validated['books']),
                'user_id' => Auth::user()->id
            ]);

            $purchase->purchase_date = $purchaseDate . $purchase->id;
            $purchase->save();

            // Create purchase details
            foreach ($validated['books'] as $book) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'book_id' => $book['book_id'],
                    'user_id' => Auth::user()->id,
                    'quantity' => $book['quantity'],
                    'per_price' => $book['per_price'],
                    'price' => $book['quantity'] * $book['per_price'],
                ]);
            }

            // Clear cart items for user & book
            foreach ($validated['books'] as $book) {
                Cart::where('user_id', Auth::user()->id)
                    ->where('book_id', $book['book_id'])
                    ->delete();
            }

            DB::commit();

            // Send confirmation email to the user ->cc('cbt.reg@senmonkyouiku.co.jp')
            Mail::to(Auth::user()->email)->queue(new PurchaseSuccessfulMail($purchase));

            return response()->json([
                'message' => 'Purchase created successfully.',
                'purchase_id' => $purchase->id,
                'purchase_date' => $purchase->date,

            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create purchase.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function list(Request $request)
    {
        $query = Purchase::with(['details.book', 'user']); // eager load user and details.book

        // Filter by purchase id
        if ($request->filled('purchase_id')) {
            $query->where('purchase_date', $request->purchase_id);
        }

        // Filter by user's name or email
        if ($request->filled('name') || $request->filled('email')) {
            $query->whereHas('user', function ($q) use ($request) {
                if ($request->filled('name')) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                }
                if ($request->filled('email')) {
                    $q->where('email', 'like', '%' . $request->email . '%');
                }
            });
        }

        // Filter by purchase created_at date
        if ($request->filled('created_date')) {
            $query->whereDate('created_at', $request->created_date);
        }

        // Sorting by created_at (default desc)
        $sortOrder = $request->input('sort', 'desc'); // allow 'asc' or 'desc' from request

        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy('created_at', $sortOrder);

        // Paginate results (e.g., 10 per page)
        $purchases = $query->paginate(perPage: 10)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $purchase = Purchase::findOrFail($request->id);
        $purchase->is_paid = $request->is_paid;
        $purchase->save();

        $query = http_build_query($request->except(['_token', '_method', 'is_paid', 'id']));

        $user = User::where("id", $purchase->user_id)->first();

        Mail::to($user->email)->queue(new PurchasePaidConfirmationMail($purchase));

        return redirect()->to(route('purchase.list') . '?' . $query)
            ->with('success', 'Payment status updated.');
    }


}
