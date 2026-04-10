<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Constructor para asegurarse de que el usuario esté autenticado
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Almacenar un nuevo comentario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Comentario añadido exitosamente.');
    }

    /**
     * Eliminar un comentario.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return back()->with('error', 'No tienes permiso para eliminar este comentario.');
        }

        $comment->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}
