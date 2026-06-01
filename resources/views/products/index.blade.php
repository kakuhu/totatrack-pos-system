@extends('layouts.admin')

@section('content')
<h2 class="text-3xl font-bold mb-6">Products List</h2>

<table class="min-w-full border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Barcode</th>
            <th class="border px-4 py-2">Category</th>
            <th class="border px-4 py-2">Cost Price</th>
            <th class="border px-4 py-2">Selling Price</th>
            <th class="border px-4 py-2">Stock</th>
            <th class="border px-4 py-2">Stock Value</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody id="productsTable">
        @foreach($products as $product)
        <tr id="productRow{{ $product->id }}">
            <td>{{ $product->name }}</td>
            <td>{{ $product->barcode }}</td>
            <td>{{ $product->category }}</td>
            <td>{{ $product->cost_price }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->stock * $product->cost_price }}</td>
            
            <td class="space-x-2">
                <button onclick="openEditModal({{ $product->id }})"
                        class="bg-yellow-500 px-3 py-1 text-white rounded hover:bg-yellow-600">
                    Edit
                </button>
                <button onclick="deleteProduct({{ $product->id }})"
                        class="bg-red-500 px-3 py-1 text-white rounded hover:bg-red-600">
                    Delete
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Edit Product Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded w-96">
        <h3 class="text-xl font-bold mb-4">Edit Product</h3>
        <form id="editProductForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="product_id" id="editProductId">

            <div class="mb-2">
                <label class="block mb-1">Name</label>
                <input type="text" name="name" id="editName" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-2">
                <label class="block mb-1">Barcode</label>
                <input type="text" name="barcode" id="editBarcode" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-2">
                <label class="block mb-1">Category</label>
                <input type="text" name="category" id="editCategory" class="border rounded w-full p-2" required>
            </div>
            <div class="mb-2">
                <label class="block mb-1">Cost Price</label>
                <input type="number" name="cost_price" id="editCostPrice" class="border rounded w-full p-2" step="0.01" required>
            </div>
            <div class="mb-2">
                <label class="block mb-1">Selling Price</label>
                <input type="number" name="price" id="editPrice" class="border rounded w-full p-2" step="0.01" required>
            </div>
            <div class="mb-2">
                <label class="block mb-1">Stock</label>
                <input type="number" name="stock" id="editStock" class="border rounded w-full p-2" required>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeEditModal()" class="bg-gray-400 px-4 py-2 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
let products = @json($products);

// Open modal and populate fields
function openEditModal(id) {
    const product = products.find(p => p.id === id);
    document.getElementById('editProductId').value = product.id;
    document.getElementById('editName').value = product.name;
    document.getElementById('editBarcode').value = product.barcode;
    document.getElementById('editCategory').value = product.category;
    document.getElementById('editCostPrice').value = product.cost_price;
    document.getElementById('editPrice').value = product.price;
    document.getElementById('editStock').value = product.stock;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// AJAX form submit
document.getElementById('editProductForm').addEventListener('submit', function(e){
    e.preventDefault();
    const id = document.getElementById('editProductId').value;

    let formData = new FormData(this);
    formData.append('_method', 'PUT'); // Important! Laravel needs this for PUT

    fetch(`/products/${id}`, {
        method: "POST",  // still POST but with _method=PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        // Update row in table
        const row = document.getElementById(`productRow${id}`);
        row.children[0].innerText = formData.get('name');
        row.children[1].innerText = formData.get('barcode');
        row.children[2].innerText = formData.get('category');
        row.children[3].innerText = formData.get('cost_price');
        row.children[4].innerText = formData.get('price');
        row.children[5].innerText = formData.get('stock');

        closeEditModal();
        alert(data.success);
    })
    .catch(err => console.error(err));
});


function deleteProduct(id) {

    if (!confirm('Are you sure you want to delete this product?')) {
        return;
    }

    fetch(`/products/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        }
    })

    .then(res => res.json())

    .then(data => {

        document.getElementById(`productRow${id}`).remove();

        alert(data.success);
    });
}

</script>
@endsection