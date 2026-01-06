@extends('layouts.app')

@section('content')
<style>
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #9ca3af !important; /* Force gray when disabled */
    }
    .total-row {
        margin-top: 1rem;
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        color: var(--primary);
    }
    .empty-cart-msg {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
        font-style: italic;
    }
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="card">
    <h2 style="margin-bottom: 2rem;">New Transaction</h2>

    @if($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 2.5rem; align-items: start;">
        
        <!-- Selection Form -->
        <div style="background: #f9fafb; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.1rem; color: #4b5563;">Add Item</h3>
            
            <div class="form-group">
                <label for="product_select">Product</label>
                <select id="product_select" class="form-control" style="width: 100%;">
                    <option value="">Select Product...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                                data-name="{{ $product->name }}" 
                                data-price="{{ $product->price }}" 
                                data-stock="{{ $product->stock }}">
                            {{ $product->name }} (Rp {{ number_format($product->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <small style="color: #6b7280; display: block; margin-top: 0.25rem;">Stock available: <span id="stock-display">-</span></small>
            </div>
            
            <div class="form-group">
                <label for="qty_input">Quantity</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="number" id="qty_input" value="1" min="1" class="form-control" style="flex: 1;">
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem;">
                <button type="button" onclick="addToCart()" class="btn btn-primary" style="flex: 1;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add to Cart
                </button>
                <span id="add-feedback" style="color: var(--success); font-weight: 500; opacity: 0; transition: opacity 0.3s; display: flex; align-items: center; gap: 0.25rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Added!
                </span>
            </div>
        </div>

        <!-- Cart Summary -->
        <div>
            <div class="flex-between" style="margin-bottom: 1rem;">
                <h3 style="margin: 0;">Current Order</h3>
                <span id="item-count" class="text-muted text-sm">0 items</span>
            </div>

            <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                <table id="cart-table">
                    <thead>
                        <tr style="background: #f1f5f9;">
                            <th style="width: 40%;">Product</th>
                            <th style="width: 20%; text-align: center;">Qty</th>
                            <th style="width: 30%; text-align: right;">Subtotal</th>
                            <th style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JS generated rows -->
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                <div style="display: flex; flex-direction: column; gap: 0.75rem; border-top: 2px solid #f3f4f6; padding-top: 1.5rem;">
                    <div class="flex-between" style="color: #64748b;">
                        <span>Subtotal</span>
                        <span id="cart-subtotal" style="font-weight: 600;">Rp 0</span>
                    </div>
                    <div class="flex-between" style="color: var(--success);">
                        <span>Discount (15%)</span>
                        <span id="cart-discount">- Rp 0</span>
                    </div>
                </div>

                <div class="flex-between total-row">
                    <span style="font-size: 1.1rem; font-weight: 600;">GRAND TOTAL</span>
                    <span id="cart-grand-total" style="font-size: 1.75rem; font-weight: 800;">Rp 0</span>
                </div>
                
                <div id="discount-info" style="text-align: center; color: #94a3b8; font-size: 0.875rem; margin-top: 0.5rem;">
                    Discount applies if total > Rp 100.000
                </div>

                <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form" style="margin-top: 1.5rem;">
                    @csrf
                    <div id="hidden-inputs"></div>
                    <button type="submit" class="btn btn-success" style="width: 100%; padding: 1rem; font-size: 1.1rem;" id="btn-process" disabled>
                        Process Transaction
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];

    // Update stock display when product changes
    document.getElementById('product_select').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const stockDisplay = document.getElementById('stock-display');
        if (option.value) {
            stockDisplay.innerText = option.getAttribute('data-stock');
        } else {
            stockDisplay.innerText = '-';
        }
    });

    function addToCart() {
        const productSelect = document.getElementById('product_select');
        const qtyInput = document.getElementById('qty_input');
        
        const productId = productSelect.value;
        const quantity = parseInt(qtyInput.value);

        if (!productId || quantity < 1) {
            alert('Please select a product and valid quantity.');
            return;
        }

        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const name = selectedOption.getAttribute('data-name');
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const stock = parseInt(selectedOption.getAttribute('data-stock'));

        // Check if already in cart
        const existingItem = cart.find(item => item.id === productId);
        let currentQtyInCart = existingItem ? existingItem.quantity : 0;

        if (currentQtyInCart + quantity > stock) {
            alert(`Insufficient stock! Only ${stock} available.`);
            return;
        }

        // Add or Update
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cart.push({
                id: productId,
                name: name,
                price: price,
                quantity: quantity
            });
        }

        updateCartUI();
        
        // Show feedback
        const feedback = document.getElementById('add-feedback');
        feedback.style.opacity = '1';
        setTimeout(() => {
            feedback.style.opacity = '0';
        }, 1500);

        // Reset inputs
        productSelect.value = "";
        qtyInput.value = 1;
        document.getElementById('stock-display').innerText = '-';
    }

    function removeFromCart(index) {
        if(confirm('Remove this item?')) {
            cart.splice(index, 1);
            updateCartUI();
        }
    }

    function updateCartUI() {
        const tbody = document.querySelector('#cart-table tbody');
        const hiddenInputs = document.getElementById('hidden-inputs');
        const btnProcess = document.getElementById('btn-process');
        const countSpan = document.getElementById('item-count');
        
        tbody.innerHTML = '';
        hiddenInputs.innerHTML = '';

        let subtotal = 0;
        let totalItems = 0;

        if (cart.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="empty-cart-msg">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 0.5rem; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <br>
                        Cart is empty
                    </td>
                </tr>`;
            btnProcess.disabled = true;
            btnProcess.style.cursor = 'not-allowed';
        } else {
            btnProcess.disabled = false;
            btnProcess.style.cursor = 'pointer';
        }

        cart.forEach((item, index) => {
            let itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            totalItems += item.quantity;

            // Render Row
            const tr = document.createElement('tr');
            tr.className = 'fade-in';
            tr.innerHTML = `
                <td>
                    <div style="font-weight: 500; color: var(--text);">${item.name}</div>
                    <div style="font-size: 0.8rem; color: #94a3b8;">@ Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</div>
                </td>
                <td style="text-align: center;">${item.quantity}</td>
                <td style="text-align: right; font-weight: 500;">Rp ${new Intl.NumberFormat('id-ID').format(itemTotal)}</td>
                <td style="text-align: center;">
                    <button type="button" onclick="removeFromCart(${index})" style="background:none; border:none; color: #ef4444; cursor:pointer; padding: 0.4rem; border-radius: 4px; transition: background 0.2s;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);

            // Add Hidden Inputs
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'products[]';
            inputId.value = item.id;

            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = 'quantities[]';
            inputQty.value = item.quantity;

            hiddenInputs.appendChild(inputId);
            hiddenInputs.appendChild(inputQty);
        });

        // Update Item Count
        countSpan.innerText = `${totalItems} items`;

        // Calculate Totals
        let discount = 0;
        if (subtotal > 100000) {
            discount = subtotal * 0.10;
        }
        let grandTotal = subtotal - discount;

        // Update Display
        document.getElementById('cart-subtotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        document.getElementById('cart-discount').innerText = '- Rp ' + new Intl.NumberFormat('id-ID').format(discount);
        document.getElementById('cart-grand-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
        
        const discountInfo = document.getElementById('discount-info');
        if (subtotal > 100000) {
            discountInfo.innerHTML = "<span style='color: var(--success); font-weight: 600;'>✓ 10% Discount Applied!</span>";
        } else {
            discountInfo.innerHTML = "Discount applies if total > Rp 100.000";
        }
    }
</script>
@endsection
