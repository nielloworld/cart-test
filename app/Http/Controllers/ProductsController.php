<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductsController extends Controller
{
    //

    private $ulticart;

    public function index()
    {
        $products = Product::all();
 
        return view('products', compact('products'));
    }
 
    public function cart()
    {
        return view('cart');
    }
    public function addToCart($id)
    {
        $product = Product::find($id);
 
        if(!$product) {
 
            abort(404);
 
        }
 
        $cart = session()->get('cart');
 
        // if cart is empty then this the first product
        if(!$cart) {
 
            $cart = [
                    $id => [
                        "name" => $product->name,
                        "quantity" => 1,
                        "price" => $product->price,
                        "photo" => $product->photo
                    ]
            ];
 
            session()->put('cart', $cart);
            $this->ulticart = session()->get('cart');
            return redirect()->back()->with('success', 'Product added to cart successfully!');
        }
 
        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {
 
            $cart[$id]['quantity']++;
 
            session()->put('cart', $cart);
            $this->ulticart = session()->get('cart');
            return redirect()->back()->with('success', 'Product added to cart successfully!');
 
        }
 
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            "photo" => $product->photo
        ];
 
        session()->put('cart', $cart);
        $this->ulticart = session()->get('cart');
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function checkout(Request $request){
        $cart = session()->get('cart');
        $total = 0;
        foreach ($cart as $item){
            $amount = $item['quantity'] * $item['price'];
            $total += $amount;
        }
        $total = $total * 100;
            // Use Stripe's library to make requests...
            \Stripe\Stripe::setApiKey('sk_test_8xxcMFYiQqqsT3iGL1s7SDsi');
            $token = $_POST['stripeToken'];
            $charge = \Stripe\Charge::create([
                'amount' => $total,
                'currency' => 'sgd',
                'description' => 'example charge',
                'source' => $token,
            ]);
            var_dump($charge);
    }

    public function update(Request $request)
    {
        if($request->id and $request->quantity)
        {
            $cart = session()->get('cart');
 
            $cart[$request->id]["quantity"] = $request->quantity;
 
            session()->put('cart', $cart);
            $this->ulticart = session()->get('cart');
            session()->flash('success', 'Cart updated successfully');
        }
    }
 
    public function remove(Request $request)
    {
        if($request->id) {
 
            $cart = session()->get('cart');
 
            if(isset($cart[$request->id])) {
 
                unset($cart[$request->id]);
 
                session()->put('cart', $cart);
                $this->ulticart = session()->get('cart');
            }
 
            session()->flash('success', 'Product removed successfully');
        }
    }

  
}
