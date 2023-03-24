<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index() 
    {
        $viewData =[];
        $viewData['title'] = 'Admin Page - Products - Online Store';
        $viewData['products'] = Product::all();
        return view('admin.product.index')->with('viewData', $viewData);
    }

    public function store(Request $req) {
        $req->validate([
            "name"=>"required|max:255",
            "description"=>"required",
            "price"=>"required|numeric|gt:0",
            "image"=>"image",
        ]);

        $newProduct = new Product();
        $newProduct->setName($req->input('name'));
        $newProduct->setDescription($req->input('description'));
        $newProduct->setPrice($req->input('price'));
        $newProduct->setImage('game.png');
        $newProduct->save();
        /* $creationData = $req->only(["name", "description", "price"]);
        $creationData["image"] = "game.png";
        Product::create($creationData); */
        if ($req->hasFile('image')) {
           $imageName = $newProduct->getId() . "." . $req->file('image')->extension();
           Storage::disk('public')->put(
            $imageName,
            file_get_contents($req->file('image')->getRealPath())
           );
           $newProduct->setImage($imageName);
           $newProduct->save();
        }

        return back();
    }

    public function delete($id) {
        Product::destroy($id);
        return back();
    }

    public function edit($id) {
        $viewData = [];
        $viewData['title'] = "Admin Page - Edit Product - Online Store";
        $viewData['product'] = Product::findOrFail($id);
        return view('admin.product.edit')->with('viewData', $viewData);
    }

    public function update(Request $req, $id) {
        $req->validate([
            "name"=>"required|max:255",
            "description"=>"required",
            "price"=>"required|numeric|gt:0",
            "image"=>"image",
        ]);

        $product = Product::findOrFail($id); 
        $product->setName($req->input('name')); 
        $product->setDescription($req->input('description')); 
        $product->setPrice($req->input('price'));

        if ($req->hasFile('image')) { 
            $imageName = $product->getId().".".$req->file('image')->extension(); 
            Storage::disk('public')->put( $imageName, file_get_contents($req->file('image')->getRealPath()) );
            $product->setImage($imageName); 
        }
        $product->save(); 
        return redirect('/admin/product');
    }
}