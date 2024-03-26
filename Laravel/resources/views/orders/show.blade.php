@extends('layouts.app')
  
  
@section('contents')
    <h1 class="mb-0">Show Order</h1>
    <hr />
    <label class="form-label">Order No : {{$order->order_code}}</label>
    <div class="row">
    
        <div class="col mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" placeholder="Type" value="{{ $product->type }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Total</label>
            <input type="text" name="total" class="form-control" placeholder="Total" value="{{ $order->total }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Quantity</label>
            <input type="text" name="quantity" class="form-control" placeholder="Quantity" value="{{ $order->quantity }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" placeholder="Descriptoin" readonly>{{ $order->description }}</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Created At</label>
            <input type="text" name="created_at" class="form-control" placeholder="Created At" value="{{ $order->created_at }}" readonly>
        </div>
        <div class="col mb-3">
            <label class="form-label">Updated At</label>
            <input type="text" name="updated_at" class="form-control" placeholder="Updated At" value="{{ $order->updated_at }}" readonly>
        </div>
    </div>
@endsection