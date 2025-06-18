<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'purchase_date' => $purchaseDate,
                'item_count' => count($validated['books']),

            ]);


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

    public function list()
    {
        $purchases = Purchase::all();
        return view('purchases.index', compact('purchases'));
    }

    public function edit(Purchase $purchase)
    {
        $purchase = Purchase::all();
        dd($purchase);
        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, Purchase $purchase)
    {          
        $purchase->is_paid = $request->is_paid;        
        $purchase->save();
        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchase.list')->with('success', 'Purchase deleted.');
    }
}
