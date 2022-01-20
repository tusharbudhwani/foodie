<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:20|unique:products',
            'calories' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'category_id' => 'required|exists:categories,id'
        ];
        $this->validate($request, $rules);

        if ($request->hasFile('image')) {
            $image = $request->image->store('images/products');
        }

        $product = Product::create([
            'name' => $request->name,
            'calories' => $request->calories,
            'image' => $image,
            'category_id' => $request->category_id
        ]);

        session()->flash('success', 'Product added successfully!');

        return redirect()->back();
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }

    public function getProductsForCategory(Request $request): JsonResponse
    {
        $products = $this->getProducts($request->category_id);
        $data = [];
        $data['products'] = $products;
        return JsonResponse::fromJsonString(json_encode($data));
    }

    private function getProducts($category_id)
    {
        return Product::where('category_id', '=', $category_id)->get();
    }
}