@extends('layouts.app')

@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])

@section('content')
<div class="card">
  <div class="card-header">
    @lang('auth.products_in_cart')
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped text-center">
      <thead>
        <tr>
          <th scope="col">@lang('auth.id')</th>
          <th scope="col">@lang('auth.name')</th>
          <th scope="col">@lang('auth.price')</th>
          <th scope="col">@lang('auth.quantity')</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($viewData["products"] as $product)
      <tr>
        <td>{{ $product->getId() }}</td>
        <td>{{ $product->getName() }}</td>
        <td>${{ $product->getPrice() }}</td>
        <td>{{ json_decode(request()->cookie('cart_products', '{}'), true)[$product->getId()] }}</td>
      </tr>
      @endforeach
      </tbody>
    </table>
    <div class="row">
      <div class="text-end">
        <a class="btn btn-outline-secondary mb-2"><b>@lang('auth.total_to_pay'):</b> ${{ $viewData["total"] }}</a>
        @if (count($viewData["products"]) > 0)
        <a href="{{ route('cart.purchase') }}" class="btn bg-primary text-white mb-2">@lang('auth.purchase')</a>
        <a href="{{ route('cart.delete') }}">
          <button class="btn btn-danger mb-2">
            @lang('auth.remove_all_products')
          </button>
        </a>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection