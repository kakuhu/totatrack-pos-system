@extends('layouts.admin')

@section('content')
<h2 class="text-3xl font-bold mb-6">Point of Sale</h2>

<div class="flex gap-6 mb-4 flex-wrap">

    <!-- Barcode -->
    <div>
        <label class="block font-semibold">Scan Barcode</label>
        <input type="text"
               id="barcodeInput"
               class="border rounded p-2 w-64"
               placeholder="Scan barcode">
    </div>

    <!-- Searchable Product -->
    <div>
        <label class="block font-semibold">Search Product</label>

        <input
            type="text"
            id="productSearch"
            list="productsList"
            class="border rounded p-2 w-64"
            placeholder="Type product name..."
        >

        <datalist id="productsList">
            @foreach($products as $product)
                <option
                    value="{{ $product->name }}"
                    data-id="{{ $product->id }}"
                    data-price="{{ $product->price }}">
                </option>
            @endforeach
        </datalist>
    </div>

    <!-- Quantity -->
    <div>
        <label class="block font-semibold">Quantity</label>
        <input type="number"
               id="quantity"
               class="border rounded p-2 w-20"
               value="1"
               min="1">
    </div>

    <!-- Add Button -->
    <div class="self-end">
        <button type="button"
                onclick="addToCart()"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Add to Cart
        </button>
    </div>

</div>

<!-- Cart -->
<h3 class="text-xl font-bold mb-2">Cart</h3>

<table class="min-w-full bg-white shadow rounded overflow-hidden mb-4">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2">Product</th>
            <th class="px-4 py-2">Price</th>
            <th class="px-4 py-2">Qty</th>
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2">Action</th>
        </tr>
    </thead>

    <tbody id="cartBody"></tbody>
</table>

<!-- Total -->
<h3 class="text-xl font-bold mb-4">
    Grand Total: ZMW <span id="grandTotal">0.00</span>
</h3>

<!-- Complete Sale -->
<button type="button"
        onclick="completeSale()"
        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
    Complete Sale
</button>

@endsection

@section('scripts')

<script>

let cart = [];

/*
|--------------------------------------------------------------------------
| ADD TO CART
|--------------------------------------------------------------------------
*/
function addToCart() {

    let searchInput = document.getElementById('productSearch');
    let productName = searchInput.value;

    let quantity = parseInt(document.getElementById('quantity').value);

    let option = [...document.querySelectorAll('#productsList option')]
        .find(opt => opt.value === productName);

    if (!option) {
        alert('Select a valid product');
        return;
    }

    let id = option.dataset.id;
    let name = option.value;
    let price = parseFloat(option.dataset.price);

    let total = price * quantity;

    // Check if item already exists
    let existing = cart.find(i => i.id == id);

    if (existing) {
        existing.quantity += quantity;
        existing.total = existing.quantity * existing.price;
    } else {
        cart.push({
            id,
            name,
            price,
            quantity,
            total
        });
    }

    renderCart();

    // Reset fields
    searchInput.value = '';
    document.getElementById('quantity').value = 1;
}

/*
|--------------------------------------------------------------------------
| RENDER CART
|--------------------------------------------------------------------------
*/
function renderCart() {

    let cartBody = document.getElementById('cartBody');

    cartBody.innerHTML = '';

    let grandTotal = 0;

    cart.forEach(item => {

        grandTotal += item.total;

        cartBody.innerHTML += `
            <tr data-id="${item.id}">
                <td class="px-4 py-2">${item.name}</td>

                <td class="px-4 py-2">
                    ${item.price.toFixed(2)}
                </td>

                <td class="px-4 py-2">

                    <button class="decrease px-2 bg-gray-300 rounded">
                        -
                    </button>

                    <span class="quantity mx-2">
                        ${item.quantity}
                    </span>

                    <button class="increase px-2 bg-gray-300 rounded">
                        +
                    </button>

                </td>

                <td class="px-4 py-2 totalCell">
                    ${item.total.toFixed(2)}
                </td>

                <td class="px-4 py-2">
                    <button class="remove px-2 bg-red-500 text-white rounded">
                        Remove
                    </button>
                </td>
            </tr>
        `;
    });

    document.getElementById('grandTotal').innerText =
        grandTotal.toFixed(2);

    attachCartEvents();
}

/*
|--------------------------------------------------------------------------
| CART EVENTS
|--------------------------------------------------------------------------
*/
function attachCartEvents() {

    // Increase quantity
    document.querySelectorAll('.increase').forEach(btn => {

        btn.onclick = function() {

            let row = this.closest('tr');

            let id = row.dataset.id;

            let item = cart.find(i => i.id == id);

            item.quantity++;

            item.total = item.quantity * item.price;

            renderCart();
        };
    });

    // Decrease quantity
    document.querySelectorAll('.decrease').forEach(btn => {

        btn.onclick = function() {

            let row = this.closest('tr');

            let id = row.dataset.id;

            let item = cart.find(i => i.id == id);

            if (item.quantity > 1) {

                item.quantity--;

                item.total = item.quantity * item.price;
            }

            renderCart();
        };
    });

    // Remove item
    document.querySelectorAll('.remove').forEach(btn => {

        btn.onclick = function() {

            let row = this.closest('tr');

            let id = row.dataset.id;

            cart = cart.filter(i => i.id != id);

            renderCart();
        };
    });
}

/*
|--------------------------------------------------------------------------
| COMPLETE SALE
|--------------------------------------------------------------------------
*/
function completeSale() {

    if (cart.length === 0) {

        alert('Cart is empty');

        return;
    }

    fetch("{{ route('sales.sell') }}", {

        method: "POST",

        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },

        body: JSON.stringify({ cart })

    })

    .then(res => {
        if (!res.ok) {
            return res.json().then(err => Promise.reject(err));
        }
        return res.json();
    })
    .then(data => {
        alert("Sale completed!");
        cart = [];
        renderCart();
    })
    .catch(error => {
        console.error('Sale failed', error);
        alert('Sale failed. Check server logs or refresh and try again.');
    });
}

/*
|--------------------------------------------------------------------------
| BARCODE SCANNER
|--------------------------------------------------------------------------
*/
document.getElementById('barcodeInput')
.addEventListener('change', function () {

    let barcode = this.value;

    fetch(`/sales/barcode/${barcode}`)

        .then(res => res.json())

        .then(product => {

            if (product.error) {

                alert("Product not found");

                return;
            }

            // Auto-fill product search
            document.getElementById('productSearch').value =
                product.name;

            document.getElementById('quantity').value = 1;

            this.value = '';
        });
});

</script>

@endsection