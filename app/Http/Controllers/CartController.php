<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
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

    public function purchase(Request $req)
    {
        $productInSession = $req->session()->get('products');
        if ($productInSession) {
            $userId = Auth::user()->getId();
            $order = new Order();
            $order->setUserId($userId);
            $order->setTotal(0);
            $order->save();

            $total = 0;
            $productsInCart = Product::findMany(array_keys($productInSession));
            foreach ($productsInCart as $product) {
                $quantity = $productInSession[$product->getId()];
                $item = new Item();
                $item->setQuantity($quantity);
                $item->setPrice($product->getPrice());
                $item->setProductId($product->getId());
                $item->setOrder($order->getId());
                $item->save();
                $total = $total + ($product->getPrice() * $quantity);
            }
            $order->setTotal($total);
            $order->save();

            $newBalance = Auth::user()->getBalance() - $total;
            Auth::user()->setBalance($newBalance);
            Auth::user()->save();

            $request->session()->forget('products');
            $viewData = [];
            $viewData["title"] = "Purchase - Online Store";
            $viewData["subtitle"] = "Purchase Status";
            $viewData["order"] = $order;
            return view('cart.purchase')->with("viewData", $viewData);
        } else {
            return redirect('/cart');
        }
    }
}
