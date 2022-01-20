<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Unit;
use App\Models\Storeroom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $categories = Category::all();
        $units = Unit::all();

        return view('storeroom.index', compact(['categories', 'units']));
    }

    public function store(Request $request)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|numeric',
            'unit_id' => 'required|exists:units,id'
        ];
        $this->validate($request, $rules);

        $productExist = Storeroom::where('user_id', auth()->user()->id)
                            ->where('product_id' ,$request->product_id)
                            ->isNotPurchased()
                            ->exists();
        if(!$productExist){
            Storeroom::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_id' => $request->unit_id
            ]);
            return true;
        }

        return false;
    }

    public function update(Request $request, User $user, Product $product)
    {
        $rules = [
            'quantity' => 'required|numeric',
            'unit_id' => 'required|exists:units,id'
        ];
        $this->validate($request, $rules);

        $storedProduct = Storeroom::where('product_id', $product->id)->where('user_id', auth()->user()->id)->first();

        $storedProduct->update([
            'quantity' => $request->quantity,
            'unit_id' => $request->unit_id
        ]);

        session()->flash('success', 'Product Updated Successfully');
        return redirect()->back();
    }
    public function grocery()
    {
        $categories = Category::all();
        $groceryList = $this->getProductsForUserInGroceryList();
        $units = Unit::all();

        $storeroomProducts = Storeroom::where('user_id', auth()->user()->id)
                                ->isPurchased()
                                ->with('product')
                                ->get();

        $productsInCategories = [];
        $i = 0;
        if(count($storeroomProducts) > 0){
            foreach($storeroomProducts as $storeroomProduct){
                foreach($storeroomProduct->product->category->products as $product) {
                    if(! in_array($product, $productsInCategories)) {
                        array_push($productsInCategories, $product);
                        $i++;
                    }
                    if($i > 3) {
                        break;
                    }
                }
            }
        }else{
            $categories = Category::all()->random(10);
            foreach($categories as $key => $category){
                foreach($category->products as $product) {
                    if(! in_array($product, $productsInCategories)) {
                            array_push($productsInCategories, $product);
                        $i++;
                    }
                    if($i > 3) {
                        break;
                    }
                }
            }
        }

        array_unique($productsInCategories);

        return view('grocery.index', compact(['categories', 'groceryList', 'units', 'productsInCategories']));
    }

    public function getGroceryList(Request $request): JsonResponse
    {
        $products = $this->getProductsForUserInGroceryList();
        $data = [];
        $data['products'] = $products;
        return JsonResponse::fromJsonString(json_encode($data));
    }

    private function getProductsForUserInGroceryList()
    {
        $products = Storeroom::where('user_id', auth()->user()->id)->isNotPurchased()->latest('updated_at')->with('product', 'unit')->get();
        return $products;
    }

    public function getPurchasedProductsJson(): JsonResponse
    {
        $products = Storeroom::where('user_id', auth()->user()->id)
                                    ->isPurchased()
                                    ->isNotConsumed()
                                    ->with('product', 'unit')
                                    ->oldest('expiry_date')->get();

                                    // $products = Storeroom::where('user_id', auth()->user()->id)
                                    //     ->isPurchased()
                                    //     ->where('isConsumed', 0)
                                    //     ->latest('updated_at')
                                    //     ->with('product', 'unit')
                                    //     ->get();

        $data = [];
        $data['products'] = $products;
        return JsonResponse::fromJsonString(json_encode($data));
    }

    public function destroy(User $user, Product $product)
    {
        $storeroomProduct =
                            Storeroom::where('user_id', $user->id)
                                ->where('product_id', $product->id)
                                ->where('isPurchased', 0)
                                ->where('isConsumed', 0)
                                ->first();

        $storeroomProduct->delete();

    }

    public function deleteProduct(Storeroom $storeroom)
    {
        $storeroomProduct = Storeroom::findOrFail($storeroom->id);
        $storeroomProduct->delete();
    }


    public function markAsPurchased(Request $request, User $user, Product $product)
    {
        $rules = [
            'expiry_date' => 'required'
        ];

        $this->validate($request, $rules);

        $storedProduct =
                 Storeroom::where('user_id', auth()->user()->id)
                        ->where('product_id', $product->id)
                        ->isNotPurchased()
                        ->first();

        $storedProduct->update([
            'isPurchased' => Storeroom::PURCHASED,
            'expiry_date' => $request->expiry_date
        ]);

        session()->flash('success', 'Product Added In Your Storeroom!');
        return redirect()->back();
    }

    public function purchase(Request $request, User $user){
        $rules = [
            'product_id' => 'required|exists:products,id',
            'unit_id' => 'required|exists:units,id',
            'quantity' => 'required|numeric',
            'expiry_date' => 'required'
        ];

        $this->validate($request, $rules);

        $product = Storeroom::where('user_id', auth()->user()->id)
                                ->where('product_id', $request->product_id)
                                ->isNotPurchased()
                                ->where('isConsumed', 0)
                                ->exists();

        if($product){
            $product->expiry_date = $request->expiry_date;
            $product->isPurchased = 1;
            $product->quantity = $request->quantity;
            $product->unit_id = $request->unit_id;
            $product->save();
        }
        else{
            $StoreroomProductPurchased = Storeroom::create([
                'user_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_id' => $request->unit_id,
                'expiry_date' => $request->expiry_date,
                'isPurchased' => 1
            ]);
        }
    }
    public function markAsConsumed(Request $request, User $user, Product $product)
    {

        $storedProduct =
                 Storeroom::where('user_id', auth()->user()->id)
                        ->where('product_id', $product->id)
                        ->isPurchased()
                        ->isNotConsumed()
                        ->first();

        $storedProduct->update([
            'isConsumed' => Storeroom::CONSUMED,
            'consumed_date' => now()
        ]);

        session()->flash('success', 'Product Marked as Consumed!');
        return redirect()->back();
    }

    public function recommendationProducts(User $user){
        $storeroomProducts =
                        Storeroom::where('user_id', $user->id)
                                ->isPurchased()
                                ->with('product')
                                ->get();

        $productsInCategories = [];
        if(count($storeroomProducts) > 0){
            foreach($storeroomProducts as $storeroomProduct){
                array_push($productsInCategories, $storeroomProduct->product->category->products);
            }
        }else{
            $categories = Category::all()->random(5);
            foreach($categories as $category){
                array_push($productsInCategories, $category->products);
            }
        }

        return $productsInCategories;
    }
}










