@extends('layouts.app')
  
  
@section('contents')
    <h1 class="mb-0">All Order</h1>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <table class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Order Code</th>
                <th>Description</th>
                <th>Ordered Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $order->productTypeName }}</td>
                        <td class="align-middle">{{ $order->quantity }}</td>
                        <td class="align-middle">{{ $order->total }}</td>
                        <td class="align-middle">{{ $order->order_code }}</td>
                        <td class="align-middle">{{ $order->description }}</td>  
                        <td class="align-middle">{{ $order->created_at }}</td>  
                        <td class="align-middle">
    <form action="{{ route('orders.accept', $order->id) }}" method="POST" >
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-primary{{ $order->accept ? ' disabled' : '' }}" {{ $order->accept ? 'disabled' : '' }}>Accept</button>
    </form>
</td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Order not found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection