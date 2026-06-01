@extends('layouts.app')

@section('content')

<h2>Point of Sale</h2>

<!-- Barcode Input -->
<div>
    <label>Scan Barcode</label>
    <input type="text" id="barcodeInput" placeholder="Scan barcode">
</div>

<!-- OR Dropdown -->
<div>
    <label>Select Product</label>
    <select id="productSelect">
        <option value="">-- Select Product --</option>
        @foreach($products as $product)
            <option 
                value="{{ $product->id }}"
                data-name="{{ $product->name }}"
                data-price="{{ $product->price }}"
            >
                {{ $product->name }} - ${{ $product->price }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label>Quantity</label>
    <input type="number" id="quantity" value="1" min="1">
</div>

<button type="button" onclick="addToCart()">Add to Cart</button>

<h3>Cart</h3>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody id="cartBody"></tbody>
</table>

<h3>Total: $<span id="grandTotal">0.00</span></h3>

<button type="button" onclick="completeSale()">Complete Sale</button>

@endsection

@section('scripts')
<script>

let cart = [];

function addToCart() {
    let select = document.getElementById('productSelect');
    let quantity = parseInt(document.getElementById('quantity').value);

    if (!select.value) {
        alert('Select a product');
        return;
    }

    let name = select.options[select.selectedIndex].dataset.name;
    let price = parseFloat(select.options[select.selectedIndex].dataset.price);

    let total = price * quantity;

    cart.push({
        id: select.value,
        name: name,
        price: price,
        quantity: quantity,
        total: total
    });

    renderCart();
}

function renderCart() {
    let cartBody = document.getElementById('cartBody');
    cartBody.innerHTML = '';

    let grandTotal = 0;

    cart.forEach(item => {
        grandTotal += item.total;

        cartBody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>${item.price}</td>
                <td>${item.quantity}</td>
                <td>${item.total.toFixed(2)}</td>
            </tr>
        `;
    });

    document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
}

function completeSale() {
    if (cart.length === 0) {
        alert('Cart is empty');
        return;
    }

    fetch("{{ route('pos.sell') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ cart: cart })
    })
    .then(res => res.json())
    .then(data => {
        alert("Sale completed!");
        cart = [];
        renderCart();
    });
}

document.getElementById('barcodeInput')
    .addEventListener('change', function () {

    let barcode = this.value;

    fetch(`/pos/barcode/${barcode}`)
        .then(res => res.json())
        .then(product => {

            if (product.error) {
                alert("Product not found");
                return;
            }

            cart.push({
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: 1,
                total: parseFloat(product.price)
            });

            renderCart();
            this.value = '';
        });
});
</script>
@endsection