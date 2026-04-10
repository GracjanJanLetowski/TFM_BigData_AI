<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $featuredProducts = Product::with('ratings') 
            ->get()
            ->sortByDesc(function($product) {
                return $product->averageRating(); 
            })
            ->take(3); 

        // Big Data / AI Integration
        $aiRecommendedProducts = collect();
        if (Auth::check()) {
            try {
                // Hacer petición a la API de FastAPI corriendo en el puerto local 8001
                $response = Http::timeout(3)->get('http://127.0.0.1:8001/recommend/' . Auth::user()->id);
                
                if ($response->successful()) {
                    $recommendedIds = $response->json()['recommended_product_ids'] ?? [];
                    
                    if (!empty($recommendedIds)) {
                        // Respetar el orden devuelto por Machine Learning
                        $placeholders = implode(',', array_fill(0, count($recommendedIds), '?'));
                        $aiRecommendedProducts = Product::whereIn('id', $recommendedIds)
                            ->orderByRaw("FIELD(id, $placeholders)", $recommendedIds)
                            ->take(4)
                            ->get();
                    }
                }
            } catch (\Exception $e) {
                // Si la IA está apagada, no rompemos la app, simplemente no aparece la sección
            }
        }

        return view('home', compact('featuredProducts', 'aiRecommendedProducts'));
    }
    
    public function about()
    {
        return view('sobre-nosotros');
    }
}
