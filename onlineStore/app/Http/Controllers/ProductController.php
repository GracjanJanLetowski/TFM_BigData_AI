<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate(12);
    
        if ($request->wantsJson()) {
            $productsWithImageUrls = $products->items();
            
            foreach ($productsWithImageUrls as $product) {
                $product->image_url = asset('storage/' . $product->getImage());  
            }
    
            return response()->json([
                'products' => $productsWithImageUrls, 
                'nextPageUrl' => $products->nextPageUrl()
            ]);
        }
    
        $viewData = [
            "title" => "Productos - Tienda Online",
            "subtitle" => "Lista de productos",
            "products" => $products
        ];
    
        return view('product.index')->with("viewData", $viewData);
    }
    
    
    

    public function show($id)
    {
        $viewData = [];
        $product = Product::findOrFail($id);
    
        $productUrl = route('product.show', ['id' => $product->getId()]);
    
        $facebookUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($productUrl);
        $twitterUrl = 'https://twitter.com/intent/tweet?text=' . urlencode($product->getName()) . '&url=' . urlencode($productUrl);
        $whatsappUrl = 'https://wa.me/?text=' . urlencode($product->getName() . ' ' . $productUrl);
    
        $viewData["title"] = $product->getName() . " - Tienda Online";
        $viewData["subtitle"] = $product->getName() . " - Información de los productos";
        $viewData["product"] = $product;
        $viewData["facebookUrl"] = $facebookUrl;
        $viewData["twitterUrl"] = $twitterUrl;
        $viewData["whatsappUrl"] = $whatsappUrl;

        // --- NUEVO: ANALISIS DE SENTIMIENTO (NLP) ---
        $viewData["sentiment"] = null;
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(2)->get("http://127.0.0.1:8001/sentiment/analyze/{$id}");
            if ($response->successful()) {
                $viewData["sentiment"] = $response->json();
            }
        } catch (\Exception $e) { }
    
        return view('product.show')->with("viewData", $viewData);
    }
}
