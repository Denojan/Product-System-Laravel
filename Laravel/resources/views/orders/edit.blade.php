@extends('layouts.app')
  
  
@section('contents')
    <h1 class="mb-0">Edit Order</h1>
    <hr />
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col mb-3">
            <input type="text" id="type" name="type" class="form-control" placeholder="Type" value="{{$product->type}}" readonly>
            </div>
            <div class="col mb-3">
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $order->quantity }}" placeholder="Quantity" min="0">
                <span id="quantityError" class="text-danger"></span>
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
            <input type="text" id="total" name="total" class="form-control" value="{{ $order->total }}" placeholder="total" readonly>
    <label>Price per item : </label><label id="price" name="price"></label>
            </div>
            <div class="col mb-3">
               
                <textarea class="form-control" name="description" placeholder="Descriptoin" >{{ $order->description }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="d-grid">
                <button class="btn btn-warning">Update</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
       document.addEventListener('DOMContentLoaded', function() {
    var price = parseFloat("{{$product->price}}"); // Get the product price
    document.getElementById('price').textContent = price; // Set the product price

    document.getElementById('quantity').addEventListener('input', function() {
        validateQuantity();
        calculateTotal();
    });
    
    document.getElementById('orderForm').addEventListener('submit', function(event) {
        if (!validateQuantity()) {
            event.preventDefault();
        }
    });

    // Initially validate the quantity
    validateQuantity();
});

function validateQuantity() {
    var quantity = parseFloat(document.getElementById('quantity').value);
    var quantityAvailable = parseFloat("{{ $product->quantity }}");
    var previousOrderQuantity = parseFloat("{{ $order->quantity }}");
 
    var totalAvailable = quantityAvailable + previousOrderQuantity;

    var quantityError = document.getElementById('quantityError');
    quantityError.textContent = ''; 

    if (isNaN(quantity) || quantity < 0) {
        quantityError.textContent = 'Quantity cannot be negative';
        return false;
    }

    if (quantity > totalAvailable) {
        quantityError.textContent = 'Quantity exceeds. Available stock: ' + totalAvailable;
        return false;
    }

    return true;
}

function calculateTotal() {
    var price = parseFloat(document.getElementById('price').textContent) || 0; // Get textContent instead of value
    var quantity = parseFloat(document.getElementById('quantity').value) || 0;
    var total = price * quantity;
    document.getElementById('total').value = total.toFixed(2);
}

    </script>
@endsection
