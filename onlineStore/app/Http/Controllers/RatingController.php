<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Almacenar o actualizar una valoración para un producto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $existingRating = Rating::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingRating) {
            $existingRating->update([
                'rating' => $request->rating,
            ]);
        } else {
            Rating::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'rating' => $request->rating,
            ]);
        }

        return back()->with('success', 'Valoración registrada correctamente.');
    }

    /**
     * Eliminar una valoración.
     *
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        if ($rating->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes permiso para eliminar esta valoración.');
        }

        $rating->delete();

        return back()->with('success', 'Valoración eliminada.');
    }
}
