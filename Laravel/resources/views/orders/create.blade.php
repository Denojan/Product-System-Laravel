@extends('layouts.app')
  
  
@section('contents')
    <h1 class="mb-0">Add Order</h1>
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
    <form action="{{ route('orders.store', auth()->user()->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <select id="productType" name="product_type_id" class="form-control">
                    <option value="">Select Product Type</option>
                    @foreach($productTypes as $productType)
                        <option value="{{ $productType->id }}" data-price="{{ $productType->price }}" data-quantity="{{ $productType->quantity }}">{{ $productType->type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
            <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Quantity" min="0">
                <span id="quantityError" class="text-danger"></span>
            </div>
        </div>
        <div class="row mb-3">
        <div class="col">
           
    <input type="text" id="total" name="total" class="form-control" placeholder="total" readonly>
    <label>Price per item :</label><label id="price" name="price"></label>
</div>

            <div class="col">
                <textarea class="form-control" name="description" placeholder="Description"></textarea>
            </div>
        </div>
 
        <div class="row">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('productType').addEventListener('change', function() {
                var price = parseFloat(this.options[this.selectedIndex].getAttribute('data-price'));
                var quantityAvailable = parseFloat(this.options[this.selectedIndex].getAttribute('data-quantity'));
                document.getElementById('price').textContent = price || ''; // Set textContent instead of value
                document.getElementById('quantity').setAttribute('max', quantityAvailable); // Set maximum quantity
                calculateTotal();
            });

            document.getElementById('quantity').addEventListener('input', function() {
                validateQuantity();
                calculateTotal();
            });

            document.getElementById('orderForm').addEventListener('submit', function(event) {
                if (!validateQuantity()) {
                    event.preventDefault(); 
                }
            });
        });

        function validateQuantity() {
            var quantity = parseFloat(document.getElementById('quantity').value);
            var quantityAvailable = parseFloat(document.getElementById('quantity').getAttribute('max'));

            var quantityError = document.getElementById('quantityError');
            quantityError.textContent = ''; // Reset error message

            if (isNaN(quantity) || quantity < 0) {
                quantityError.textContent = 'Quantity cannot be negative';
                return false;
            }

            if (quantity > quantityAvailable) {
                quantityError.textContent = 'Quantity exceeds. Available stock :'+quantityAvailable;
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
