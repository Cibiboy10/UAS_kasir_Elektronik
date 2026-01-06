@extends('layouts.app')

@section('content')
<div class="card">
    <div class="flex-between mb-4">
        <h2>Products Management</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Product
        </a>
    </div>

    <form action="{{ route('products.index') }}" method="GET" style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem; max-width: 500px;">
        <input type="text" name="search" placeholder="Search product name..." value="{{ request('search') }}" style="flex-grow: 1;">
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search'))
            <a href="{{ route('products.index') }}" class="btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text);">Clear</a>
        @endif
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td style="font-weight: 500;">{{ $product->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        @if($product->stock <= 5)
                            <span style="color: var(--danger); font-weight: bold;">{{ $product->stock }} (Low)</span>
                        @else
                            {{ $product->stock }}
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('products.edit', $product) }}" class="btn" style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.5rem; font-size: 0.875rem;">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; font-size: 0.875rem;">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
