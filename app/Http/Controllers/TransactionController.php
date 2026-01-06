<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'product'])->latest();

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        $transactions = $query->get();

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 1)->get(); // Only products with enough stock
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        $cartItems = [];
        $grandTotal = 0;

        // 1. Validate Stock & Calculate Total
        foreach ($request->products as $index => $productId) {
            $quantity = $request->quantities[$index];
            $product = Product::findOrFail($productId);

            // Check sufficient stock
            if ($product->stock < $quantity) {
                return back()->withErrors(['error' => "Insufficient stock for {$product->name}. available: {$product->stock}"]);
            }

            // Check "Stock cannot be 1" rule
            if (($product->stock - $quantity) == 1) {
                return back()->withErrors(['error' => "Transaction for {$product->name} failed. Stock cannot remain exactly 1."]);
            }

            $subtotal = $product->price * $quantity;
            $grandTotal += $subtotal;

            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }

        // 2. Determine Discount
        $discountPercentage = 0;
        if ($grandTotal > 100000) {
            $discountPercentage = 0.15;
        }

        // 3. Process Transactions
        foreach ($cartItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            $subtotal = $item['subtotal'];
            
            // Apply discount to item
            $discountAmount = $subtotal * $discountPercentage;
            $finalPrice = $subtotal - $discountAmount;

            Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'total_price' => $finalPrice,
                'transaction_date' => now(),
            ]);

            // Deduct Stock
            $product->decrement('stock', $quantity);
        }

        $formattedTotal = number_format($grandTotal * (1 - $discountPercentage), 0, ',', '.');
        $msg = "Transaction successful! Total Paid: Rp {$formattedTotal}";
        if ($discountPercentage > 0) {
            $msg .= " (15% Discount Applied)";
        }

        return redirect()->route('transactions.index')->with('success', $msg);
    }
}