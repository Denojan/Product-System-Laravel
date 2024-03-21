<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::orderBy('created_at','DESC')->get();
        return view('products.index',compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required',
            'product_code' => 'required',
            'description' => 'required'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Product::create($request->all());
 
        return redirect()->route('products')->with('success', 'Product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       

        $product = Product::findOrFail($id);
        return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required',
            'product_code' => 'required',
            'description' => 'required'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Check if any fields have changed
        $oldValues = [
            'title' => $product->title,
            'price' => $product->price,
            'product_code' => $product->product_code,
            'description' => $product->description,
        ];
        
        $newValues = $request->only(['title', 'price', 'product_code', 'description']);
        
        // Check if any fields have changed
        $changes = array_diff_assoc($newValues, $oldValues);
  
    
        // If no changes, display message
        if (empty($changes)) {
            return redirect()->back()->with('error', 'No changes were made. Same value is added');
        }
        $product->update($newValues);
        return redirect()->route('products')->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products')->with('success','Product deleted successfully');
    }
}
