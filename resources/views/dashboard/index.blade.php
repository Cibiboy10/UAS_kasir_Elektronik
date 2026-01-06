@extends('layouts.app')

@section('content')
<h2 class="mb-4">Dashboard Overview</h2>

<div class="stats-grid">
    <!-- Total Sales -->
    <div class="card stat-card" style="border-left: 4px solid var(--primary); color: var(--primary);">
        <div class="flex-between">
            <div>
                <span class="text-sm font-bold opacity-75">TOTAL SALES</span>
                <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0 0;">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            </div>
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>

    <!-- Today's Sales -->
    <div class="card stat-card" style="border-left: 4px solid var(--success); color: var(--success);">
        <div class="flex-between">
            <div>
                <span class="text-sm font-bold opacity-75">TODAY'S SALES</span>
                <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0 0;">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
            </div>
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
    </div>

    <!-- Total Transactions -->
    <div class="card stat-card" style="border-left: 4px solid #a855f7; color: #a855f7;">
        <div class="flex-between">
            <div>
                <span class="text-sm font-bold opacity-75">TRANSACTIONS</span>
                <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0 0;">{{ number_format($totalTransactions) }}</p>
            </div>
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
    </div>

    <!-- Total Products -->
    <div class="card stat-card" style="border-left: 4px solid #eab308; color: #eab308;">
        <div class="flex-between">
            <div>
                <span class="text-sm font-bold opacity-75">TOTAL PRODUCTS</span>
                <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0 0;">{{ number_format($totalProducts) }}</p>
            </div>
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
    
    <!-- Left Column: Recent Transactions -->
    <div class="card">
        <div class="flex-between mb-4">
            <h3>Recent Transactions</h3>
            <a href="{{ route('transactions.index') }}" class="text-sm" style="color: var(--primary); text-decoration: none; font-weight: 600;">View All &rarr;</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                    <tr>
                        <td class="text-sm text-muted">{{ $transaction->transaction_date }}</td>
                        <td style="font-weight: 500;">{{ $transaction->product->name }}</td>
                        <td style="text-align: right; font-weight: 600;">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 2rem;">No transactions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Stats & Alerts -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Top Product -->
        <div class="card">
            <h3 class="text-sm text-muted uppercase tracking-wider mb-2">⭐ Best Selling Product</h3>
            @if($frequentProduct)
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background: #eef2ff; padding: 0.75rem; border-radius: 0.5rem; color: var(--primary);">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <p style="font-size: 1.125rem; font-weight: 700; margin: 0;">{{ $frequentProduct->name }}</p>
                        <p class="text-sm text-muted" style="margin: 0;">Most popular choice</p>
                    </div>
                </div>
            @else
                <p class="text-muted">No data available.</p>
            @endif
        </div>

        <!-- Low Stock Alert -->
        @if($lowStockProducts->count() > 0)
        <div class="card" style="border: 1px solid #fee2e2; background: #fff5f5;">
            <div class="flex-between mb-2">
                <h3 class="text-sm font-bold" style="color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Low Stock Warning
                </h3>
            </div>
            <ul style="margin: 0; padding-left: 0; list-style: none;">
                @foreach($lowStockProducts as $product)
                    <li style="padding: 0.5rem 0; border-bottom: 1px solid #fecaca; display: flex; justify-content: space-between; font-size: 0.875rem; color: #7f1d1d;">
                        <span>{{ $product->name }}</span>
                        <span style="font-weight: bold; background: #fee2e2; padding: 0.1rem 0.4rem; border-radius: 4px;">{{ $product->stock }} left</span>
                    </li>
                @endforeach
            </ul>
        </div>
        @else
        <div class="card" style="border: 1px solid #bbf7d0; background: #f0fdf4;">
            <div style="display: flex; align-items: center; gap: 0.75rem; color: #166534;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-bold text-sm">Stock levels are healthy!</span>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
