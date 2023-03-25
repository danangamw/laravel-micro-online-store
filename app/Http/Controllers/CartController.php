<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $req)
    {
        $total = 0;
        $productsInCart = [];

        $productInSession = $req->session()->get('products');
        if ($productInSession) {
            $productsInCart = Product::findMany(array_keys($productInSession));
            $total = Product::sumPricesByQuantities($productsInCart, $productInSession);
        }

        $viewData = [];
        $viewData['title'] = 'Cart - Online Store';
        $viewData['subtitle'] = 'Shopping Cart';
        $viewData['total'] = $total;
        $viewData['products'] = $productsInCart;
        return view('cart.index')->with('viewData', $viewData);
    }

    public function add(Request $req, $id)
    {
        $products = $req->session()->get('products');
        $products[$id] = $req->input('quantity');
        $req->session()->put('products', $products);

        return redirect('/cart');
    }

    public function delete(Request $req)
    {
        $req->session()->forget('products');
        return back();
    }
}