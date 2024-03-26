<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductTypeController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); 
     
        $product = ProductType::where('user_id', $userId)->orderBy('created_at', 'DESC')->get();
        return view('productstype.index',compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productstype.createtype');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, String $id)
    {
        $userId = User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required'
        ]);
       
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       
       
       $product = ProductType::create($request->all());
       $product->user_id = $userId->id;

       // Save the changes
       $product->save();
        return redirect()->route('productstype')->with('success', 'Product type added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = ProductType::findOrFail($id);
        return view('productstype.showtype',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       

        $product = ProductType::findOrFail($id);
        return view('productstype.edittype',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = ProductType::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'description' => 'required'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Check if any fields have changed
        $oldValues = [
            'type' => $product->type,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
        ];
        
        $newValues = $request->only(['type', 'price', 'quantity', 'description']);
        
        // Check if any fields have changed
        $changes = array_diff_assoc($newValues, $oldValues);
  
    
        // If no changes, display message
        if (empty($changes)) {
            return redirect()->back()->with('error', 'No changes were made. Same value is added');
        }
        $product->update($newValues);
        return redirect()->route('productstype')->with('success','Product type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = ProductType::findOrFail($id);
        $product->delete();
        return redirect()->route('productstype')->with('success','Product type deleted successfully');
    }

    public function details($id)
{
  
    $orders = Order::where('product_type_id', $id)->get();
        foreach ($orders as $order) {
            $order->productTypeName = ProductType::findOrFail($id)->type;
        }
    Log::info($orders);
    return view('productstype.details', compact('orders'));
}
}
