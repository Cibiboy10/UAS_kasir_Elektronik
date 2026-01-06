@extends('layouts.app')

@section('content')
<div class="card">
    <div class="flex-between mb-4">
        <h2>Transaction History</h2>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            New Sale
        </a>
    </div>

    <form action="{{ route('transactions.index') }}" method="GET" style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem; max-width: 500px;">
        <input type="text" name="search" placeholder="Search cashier or product..." value="{{ request('search') }}" style="flex-grow: 1;">
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search'))
            <a href="{{ route('transactions.index') }}" class="btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text);">Clear</a>
        @endif
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Kasir</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td class="text-sm text-muted">{{ $transaction->transaction_date }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td style="font-weight: 500;">{{ $transaction->product->name }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td style="font-weight: 600;">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
