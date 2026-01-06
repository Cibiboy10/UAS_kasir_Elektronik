@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Edit Product</h2>
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom: 1rem;">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" required value="{{ old('name', $product->name) }}">
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" required min="1" value="{{ old('price', $product->price) }}">
            @error('price')
                <div style="color: red; font-size: 0.875rem;">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="stock">Stock (> 1)</label>
            <input type="number" name="stock" id="stock" required min="2" value="{{ old('stock', $product->stock) }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn" style="background: #ccc; color: black;">Cancel</a>
    </form>
</div>
@endsection
