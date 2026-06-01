@extends('layouts.admin')

@section('content')
<h2 class="text-3xl font-bold mb-6">Add Product</h2>

<form id="productForm">
    @csrf
    <div class="mb-4">
        <label class="block mb-1 font-semibold">Product Name</label>
        <input type="text" name="name" class="border rounded w-full p-2" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1 font-semibold">Barcode</label>
        <input type="text" name="barcode" class="border rounded w-full p-2" required>
    </div>
    <select name="category" class="border rounded w-full p-2" required>
        <option value="">-- Select Category --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
        @endforeach
    </select>

<div class="mb-4">
    <label class="block mb-1 font-semibold">Cost Price</label>
    <input type="number" name="cost_price" step="0.01" class="border rounded w-full p-2" required>
</div>
    <div class="mb-4">
        <label class="block mb-1 font-semibold">Selling Price</label>
        <input type="number" name="price" class="border rounded w-full p-2" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1 font-semibold">Quantity</label>
        <input type="number" name="stock" class="border rounded w-full p-2" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Add Product</button>
</form>

<div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow hidden">
    Product added successfully!
</div>

@endsection

@section('scripts')
<script>
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch("{{ route('products.store') }}", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('productForm').reset();
        let toast = document.getElementById('toast');
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2000);
    });
});
</script>
@endsection