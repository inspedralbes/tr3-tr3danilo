<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProducts()
    {
        $products = Product::all();

        // Convertir los productos a formato JSON
        $jsonProducts = json_encode($products);

        // Pasar los productos a la vista
        return view('products', compact('jsonProducts'));
    }
}

?>