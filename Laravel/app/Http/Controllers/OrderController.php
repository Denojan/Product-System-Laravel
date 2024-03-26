<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id(); 
        $orders = Order::where('user_id', $userId)->orderBy('created_at','DESC')->get();
       
        foreach ($orders as $order) {
            $order->productTypeName = ProductType::findOrFail($order->product_type_id)->type;
        }
       
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userId = Auth::id(); 

        $productTypes = ProductType::where('user_id', '!=', $userId)
        ->orderBy('created_at', 'ASC')
        ->get();
        return view('orders.create',compact('productTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, String $id)
    {
        $userId = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'quantity'=>'required',
            'description' => 'required',
            'product_type_id' => 'required',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $orderCode = Str::uuid()->toString();
        $orderData = $request->all();
        $orderData['order_code'] = $orderCode;
        $order = Order::create($orderData);
        $order->user_id = $userId->id;
        $order->save();
        $productType = ProductType::findOrFail($request->product_type_id);
        $productType->update([
            'quantity' => $productType->quantity - $request->quantity,
        ]);
        return redirect()->route('orders')->with('success', 'Product order added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        $product = ProductType::findOrFail($order->product_type_id);
        Log::info($product);
        return view('orders.show',compact('order','product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       

        $order = Order::findOrFail($id);
        $product = ProductType::findOrFail($order->product_type_id);
        return view('orders.edit',compact('order','product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'quantity'=>'required',
            'description' => 'required',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Check if any fields have changed
        $oldValues = [
            'quantity' => $order->quantity,
            'total' => $order->total,
            'description' => $order->description,
        ];
        
        $newValues = $request->only(['quantity', 'total', 'description']);
        
        // Check if any fields have changed
        $changes = array_diff_assoc($newValues, $oldValues);
  
    
        // If no changes, display message
        if (empty($changes)) {
            return redirect()->back()->with('error', 'No changes were made. Same value is added');
        }
        $order->update($newValues);
        return redirect()->route('orders')->with('success','Product order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $productType = ProductType::findOrFail($order->product_type_id);
        $productType->update([
            'quantity' => $productType->quantity + $order->quantity,
        ]);
        $order->delete();
        return redirect()->route('orders')->with('success','Product order cancelled successfully');
    }

    public function accept(string $id)
    {
        $order = Order::findOrFail($id);
        $order->accept = true; 
        $order->save(); 
        return redirect()->back()->with('success', 'Product order accepted successfully');
    }
}
