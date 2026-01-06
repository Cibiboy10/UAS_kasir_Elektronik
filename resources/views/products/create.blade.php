@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Add New Product</h2>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 1rem;">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" required value="{{ old('name') }}">
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" required min="1" value="{{ old('price') }}">
            @error('price')
                <div style="color: red; font-size: 0.875rem;">{{ $message }}</div>
            @enderror
        </div>
        <div style="margin-bottom: 1rem;">
            <label for="stock">Stock (> 1)</label>
            <input type="number" name="stock" id="stock" required min="2" value="{{ old('stock') }}">
            <small style="display: block; color: #666;">Stock must be more than 1.</small>
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
        <a href="{{ route('products.index') }}" class="btn" style="background: #ccc; color: black;">Cancel</a>
    </form>
</div>
@endsection
