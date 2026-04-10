@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Sección de bienvenida -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4" style="color: #2C3E50;">@lang('auth.welcome')</h1>
            <p class="lead text-muted">@lang('auth.welcome_message')</p>
        </div>
    </div>

    <!-- Productos destacados -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">@lang('auth.featured_products')</h2>
            <div class="row">
                @foreach($featuredProducts as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset(str_replace('/public/', '', $product->image)) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ Str::limit($product->description, 30) }}</p>
                                <p class="text-danger fs-5">${{ $product->price }}</p>
                                <p class="text-muted">@lang('auth.average_rating'): {{ number_format($product->averageRating(), 1) }} / 5</p>
                                <a href="{{ route('product.show', ['id' => $product->id]) }}" class="btn custom-btn">@lang('auth.view_details')</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SECCIÓN IA: Recomendados especialmente para ti -->
    @if(isset($aiRecommendedProducts) && $aiRecommendedProducts->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4" style="color: #8E44AD; font-weight: bold;">✨ Recomendados por nuestra IA para ti</h2>
            <div class="row">
                @foreach($aiRecommendedProducts as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm border-primary" style="background-color: #faf8ff;">
                            <img src="{{ asset(str_replace('/public/', '', $product->image)) }}" class="card-img-top product-image" style="height: 250px;" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-danger fs-5 fw-bold">${{ $product->price }}</p>
                                <a href="{{ route('product.show', ['id' => $product->id]) }}" class="btn btn-outline-primary btn-sm w-100">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Categorías principales -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">@lang('auth.main_categories')</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('img/categories/electronica.jpg') }}" class="card-img-top" alt="Category Image">
                        <div class="card-body">
                            <h5 class="card-title">@lang('auth.electronics')</h5>
                            <p class="card-text">@lang('auth.explore_electronics')</p>
                            <a href="#" class="btn custom-btn">@lang('auth.explore_category')</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('img/categories/ropa.jpg') }}" class="card-img-top" alt="Category Image">
                        <div class="card-body">
                            <h5 class="card-title">@lang('auth.clothing')</h5>
                            <p class="card-text">@lang('auth.explore_clothing')</p>
                            <a href="#" class="btn custom-btn">@lang('auth.explore_category')</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('img/categories/hogar.jpg') }}" class="card-img-top" alt="Category Image">
                        <div class="card-body">
                            <h5 class="card-title">@lang('auth.home2')</h5>
                            <p class="card-text">@lang('auth.explore_home')</p> 
                            <a href="#" class="btn custom-btn">@lang('auth.explore_category')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="mb-4" style="color: #2C3E50;">@lang('auth.cta_title')</h2>
            <p class="lead mb-4">@lang('auth.cta_message')</p>
            <a href="{{ route('product.index') }}" class="btn custom-btn btn-lg">@lang('auth.shop_now')</a>
        </div>
    </div>
</div>
@endsection

<!-- Estilos personalizados -->
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

    .text-danger {
        color: #B22222; 
    }

    .product-image {
        height: 400px;
        object-fit: cover; 
        object-position: center; 
    }
</style>

