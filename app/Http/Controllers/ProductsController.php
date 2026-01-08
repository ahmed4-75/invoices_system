<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use App\Http\Resources\SectionResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::select('id','section_name')->get();
        $sections = SectionResource::collection($sections)->resolve();
        
        $products = Product::withExists('invoicesLists')->with('section')->get();
        $products = ProductResource::collection($products)->resolve();

        // dd($products);
        return view('goods.products',compact('sections','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'description' => 'required',
            'value' => 'required|decimal:2|min:0',
            'quantity' => 'required|integer|min:0',
            'section_id' => 'required'
        ],[
            'product_name.required' => 'Enter the Product Name',
            'description.required' => 'Enter the Description',
            'value.required' => 'Enter the Price',
            'value.decimal' => 'Enter the CORRECT Price LIKE 0.00',
            'value.min' => 'Enter the CORRECT Price Minimum 0.00',
            'quantity.required' => 'Enter the Quantity',
            'quantity.integer' => 'Enter the CORRECT Quantity LIKE 5',
            'quantity.min' => 'Enter the CORRECT Quantity Minimum 0',
            'section_id.required' => 'Enter the Section Name'
        ]);

        Product::create([
            'product_name'=>$request->product_name,
            'description'=>$request->description,
            'quantity' =>$request->quantity,
            'value'=>$request->value,
            'section_id'=>$request->section_id,
            'created_by' => Auth::user()->name
        ]);
        session()->flash('add','Product Added Successfully');

        return to_route('products.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // dd($request->all());
        if($user->hasRole('admin')){
            $request->validate([
                'product_name' => 'required',
                'description'=>'required',
                'section_id'=>'required',
    
                'value'=>'required|decimal:2|min:0',
                'quantity' => 'required|integer|min:0'
            ],[
                'product_name.required'=>'Enter the Product Name',
                'description.required'=>'Enter the Description',
                'value.required'=>'Enter the Price',
                'value.decimal' => 'Enter the CORRECT Price LIKE 0.00',
                'value.min' => 'Enter the CORRECT Price Minimum 0.00',
                'quantity.required '=> 'Enter the Quantity',
                'quantity.integer '=> 'Enter the CORRECT Quantity LIKE 5',
                'quantity.min '=> 'Enter the CORRECT Quantity Minimum 0',
                'section_id.required'=>'Enter the Section Name'
            ]);
        } elseif($user->hasRole('goods_receiver')) {
            $request->validate([
                'product_name' => 'required',
                'description'=>'required',
                'section_id'=>'required',
                'value'=>['required|decimal:2|min:0',Rule::in([$product->value])],
                'quantity' => ['required|integer|min:0',Rule::in([$product->quantity])]
            ],[
                'product_name.required'=>'Enter the Product Name',
                'description.required'=>'Enter the Description',
                'section_id.required'=>'Enter the Section Name',
                'value.required'=>'Enter the Price',
                'value.decimal' => 'Enter the CORRECT Price LIKE 0.00',
                'value.min' => 'Enter the CORRECT Price Minimum 0.00',
                'value.in' => 'You are not Authorized to update the Product Value',
                'quantity.required '=> 'Enter the Quantity',
                'quantity.integer '=> 'Enter the CORRECT Quantity LIKE 5',
                'quantity.min '=> 'Enter the CORRECT Quantity Minimum 0',
                'quantity.in' => 'You are not Authorized to update the Product Quantity'
            ]);
        } else {
            abort(403, 'Unauthorized');
        }

        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'quantity' =>$request->quantity,
            'value'=>$request->value,
            'section_id'=>$request->section_id,
            'created_by' => Auth::user()->name
        ]);
        session()->flash('edit','Product Edited Successfully');
        return to_route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->invoicesLists()->exists() and $product->quantity > 0) {
            return to_route('products.index')->withErrors([
                'error' => 'This Product cannot be Deleted, it has related Invoices, or it is Unempty'
            ]);
        }
        $product->delete();
        session()->flash('delete','Product deleted Successfully');

        return to_route('products.index');
    }
}
