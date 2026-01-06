<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Totals
        $totalSales = Transaction::sum('total_price');
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $todaySales = Transaction::whereDate('transaction_date', now()->today())->sum('total_price');

        // Most Frequent
        $mostFrequent = Transaction::select('product_id')
            ->selectRaw('count(*) as count')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->with('product')
            ->first();

        $frequentProduct = $mostFrequent ? $mostFrequent->product : null;

        // Lists
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        $recentTransactions = Transaction::with(['user', 'product'])->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalSales',
            'totalProducts',
            'totalTransactions',
            'todaySales',
            'frequentProduct',
            'lowStockProducts',
            'recentTransactions'
        ));
    }
}
