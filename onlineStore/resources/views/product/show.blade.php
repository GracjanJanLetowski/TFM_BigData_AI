@extends('layouts.app')

@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Imagen del producto -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-3">
                <img src="{{ asset(str_replace('/public/', '', $viewData["product"]->getImage())) }}" class="img-fluid rounded" alt="{{ $viewData['product']->getName() }}">
            </div>
        </div>

        <!-- Información del producto -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded p-4">
                <h3 class="fw-bold">{{ $viewData["product"]->getName() }}</h3>
                <p class="price-text fs-3 fw-bold text-danger">{{ number_format($viewData["product"]->getPrice(), 2) }}€</p>
                <p class="text-muted">{{ $viewData["product"]->getDescription() }}</p>

                <!-- Formulario para añadir al carrito -->
                <form method="POST" action="{{ route('cart.add', ['id'=> $viewData['product']->getId()]) }}">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="input-group">
                                <div class="input-group-text">@lang('auth.quantity')</div>
                                <input type="number" min="1" max="10" class="form-control quantity-input" name="quantity" value="1">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn custom-btn" type="submit">@lang('auth.add_to_cart')</button>
                        </div>
                    </div>
                </form>

                <!-- Valoración Promedio -->
                <p class="mt-4">
                    <strong>@lang('auth.average_rating'):</strong>
                    {{ $viewData["product"]->averageRating() ? number_format($viewData["product"]->averageRating(), 1) : __('auth.no_ratings_yet') }}
                </p>

                <!-- NUEVO: ANALISIS DE SENTIMIENTO (IA) - Solo visible para Admin -->
                @if(Auth::user() && Auth::user()->getRole() == 'admin' && isset($viewData["sentiment"]) && $viewData["sentiment"]["sentiment"] != "no_data")
                <div class="card bg-light border-0 mb-4 shadow-sm">
                    <div class="card-body py-2">
                        <h6 class="card-title mb-1" style="font-size: 0.9rem;">
                            🧠 Inteligencia Artificial: Percepción Global
                        </h6>
                        <div class="d-flex align-items-center">
                            @if($viewData["sentiment"]["sentiment"] == "Positivo")
                                <span class="badge bg-success me-2">😀 {{ $viewData["sentiment"]["sentiment"] }}</span>
                            @elseif($viewData["sentiment"]["sentiment"] == "Negativo")
                                <span class="badge bg-danger me-2">☹️ {{ $viewData["sentiment"]["sentiment"] }}</span>
                            @else
                                <span class="badge bg-secondary me-2">😐 {{ $viewData["sentiment"]["sentiment"] }}</span>
                            @endif
                            <small class="text-muted">
                                Basado en el análisis NLP de {{ $viewData["sentiment"]["count"] }} comentarios recientes.
                            </small>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Formulario de valoración -->
                <form action="{{ route('ratings.store', $viewData['product']->getId()) }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-auto">
                            <label for="rating" class="fw-bold">@lang('auth.your_rating'):</label>
                        </div>
                        <div class="col-auto">
                            <select name="rating" required class="form-control">
                                <option value="1">1 ★</option>
                                <option value="2">2 ★★</option>
                                <option value="3">3 ★★★</option>
                                <option value="4">4 ★★★★</option>
                                <option value="5">5 ★★★★★</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn custom-btn">@lang('auth.rate')</button>
                        </div>
                    </div>
                </form>

                <!-- Botones para compartir -->
                <div class="share-buttons mt-4">
                    <h5>@lang('auth.share_this_product')</h5>
                    <a href="{{ $viewData['facebookUrl'] }}" target="_blank" class="btn btn-primary me-2">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                    <a href="{{ $viewData['twitterUrl'] }}" target="_blank" class="btn btn-info me-2">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="{{ $viewData['whatsappUrl'] }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Comentarios -->
    <div class="comments mt-5 pb-5">
        <h4 class="mb-4">@lang('auth.comments')</h4>
        @forelse ($viewData["product"]->comments as $comment)
            <div class="comment-box mb-3 p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="avatar">{{ substr($comment->user->name, 0, 1) }}</div>
                    <p class="mb-1 ms-2"><strong>{{ $comment->user->name }}</strong></p>
                </div>
                <p class="comment-text">{{ $comment->comment }}</p>
                @if ($comment->user_id == auth()->id())
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">@lang('auth.delete')</button>
                    </form>
                @endif
            </div>
        @empty
            <p>@lang('auth.no_comments_yet')</p>
        @endforelse

        <!-- Formulario para dejar comentario -->
        <div class="add-comment mt-4">
            <h4>@lang('auth.leave_a_comment')</h4>
            <form action="{{ route('comments.store', $viewData['product']->getId()) }}" method="POST">
                @csrf
                <textarea name="comment" class="form-control comment-input" placeholder="@lang('auth.write_your_comment_here')" required></textarea>
                <button type="submit" class="btn custom-btn mt-2">@lang('auth.submit_comment')</button>
            </form>
        </div>
    </div>
</div>

<!-- Estilos CSS -->
<style>
    .custom-btn {
        background-color: #FF6347;
        color: white;
        border: none;
    }

    .custom-btn:hover {
        background-color: #e5533c;
        color: white;
    }

    .price-text {
        color: #B22222;
    }

    .comment-box {
        background: #f8f9fa;
        border-radius: 8px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        background: #FF6347;
        color: white;
        font-weight: bold;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .comment-text {
        margin-top: 5px;
        font-size: 15px;
    }
</style>
@endsection
