@extends('layouts.app')
  

  
@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">List Order</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Add Order</a>
    </div>
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
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Description</th>
                <th>Order Code</th>
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
                        <td class="align-middle">{{ $order->description }}</td>  
                        <td class="align-middle">{{ $order->order_code }}</td> 
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('orders.show', $order->id) }}" type="button" class="btn btn-secondary">Detail</a>
                                @if (!$order->accept)
                                <a href="{{ route('orders.edit', $order->id)}}" type="button" class="btn btn-warning">Edit</a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Cancel?')">
                                  @csrf
                                 @method('DELETE')
                                 <button class="btn btn-danger m-0">Cancel</button>
                                </form>
                                 @else
                             
                                 <button class="btn btn-warning" disabled>Edit</button>
                                 <button class="btn btn-danger m-0" disabled>Cancel</button>
                                
                                 @endif
                            </div>
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


@section('scripts')
    <script>
       
    </script>
@endsection