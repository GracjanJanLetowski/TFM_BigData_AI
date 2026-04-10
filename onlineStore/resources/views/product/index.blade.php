@extends('layouts.app')

@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])

@section('content')
<div class="container mt-5">
    <!-- Mensaje de presentación -->
    <div class="text-center mb-4">
        <h2 class="fw-bold">@lang('auth.welcome_message_title')</h2>
        <p class="text-muted">@lang('auth.welcome_message_subtitle')</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5" id="product-list">
        @foreach ($viewData["products"] as $product)
            <div class="col product-item">
                <div class="card border-0 shadow-sm rounded overflow-hidden">
                    <div class="position-relative">
                        <img src="{{ asset(str_replace('/public/', '', $product->image)) }}" class="card-img-top img-fluid" alt="{{ $product->name }}">
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold text-truncate">{{ $product->name }}</h6>
                        <p class="price-text fw-bold fs-5">{{ number_format($product->price, 2) }}€</p>
                        <a href="{{ route('product.show', ['id'=> $product->id]) }}" class="btn btn-sm custom-btn w-100 fw-bold">@lang('auth.view_product')</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Mensaje de carga -->
<div id="loading-message" class="text-center mt-4" style="display:none;">
    <p>@lang('auth.loading_products')</p>
</div>

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

    #product-list {
        margin-bottom: 6rem;
    }

    .card-img-top {
        height: 200px;       
        width: 100%;        
        object-fit: contain; 
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let loading = false;
    let nextPageUrl = @json($viewData['products']->nextPageUrl() ?: '' );
    let productList = document.getElementById('product-list');
    let loadingMessage = document.getElementById('loading-message');

    let baseUrl = "{{ asset('/') }}";

    const observer = new IntersectionObserver(entries => {
        if (loading) return;

        if (entries[0].isIntersecting && nextPageUrl) {
            loading = true;
            loadingMessage.style.display = 'block';
            loadMoreProducts(nextPageUrl);
        }
    }, {
        rootMargin: '100px',
    });

    const lastProduct = document.querySelector('.product-item:last-child');
    if (lastProduct) observer.observe(lastProduct);

    function loadMoreProducts(url) {
        fetch(url, {
            method: 'GET',
            headers: { 'Accept': 'application/json' }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    console.error("Error en la respuesta:", text);
                    throw new Error("Respuesta no válida");
                });
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data.products) && data.products.length > 0) {
                data.products.forEach(product => {
                    let cleanImageUrl = product.image.replace('/public/', '');
                    let productItem = document.createElement('div');
                    productItem.classList.add('col', 'product-item');
                    productItem.innerHTML = `
                        <div class="card border-0 shadow-sm rounded overflow-hidden">
                            <div class="position-relative">
                                <img src="${baseUrl + cleanImageUrl}" class="card-img-top img-fluid" alt="${product.name}">
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold text-truncate">${product.name}</h6>
                                <p class="price-text fw-bold fs-5">${parseFloat(product.price).toFixed(2)}€</p>
                                <a href="/products/${product.id}" class="btn btn-sm custom-btn w-100 fw-bold">@lang('auth.view_product')</a>
                            </div>
                        </div>
                    `;
                    productList.appendChild(productItem);
                });
            }

            nextPageUrl = data.nextPageUrl;
            loading = false;
            loadingMessage.style.display = 'none';

            if (nextPageUrl) {
                const newLastProduct = document.querySelector('.product-item:last-child');
                observer.observe(newLastProduct);
            }
        })
        .catch(error => {
            console.error("Error al cargar productos:", error);
            loading = false;
            loadingMessage.style.display = 'none';
        });
    }

    productList.addEventListener('click', function(event) {
        if (event.target && event.target.matches('a.btn.custom-btn')) {
            const productId = event.target.getAttribute('href').split('/').pop();
            window.location.href = `/products/${productId}`;
        }
    });
});
</script>

@endsection
