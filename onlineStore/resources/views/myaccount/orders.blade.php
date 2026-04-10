@extends('layouts.app')

@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])

@section('content')
@forelse ($viewData["orders"] as $order)
<div class="card mb-4">
  <div class="card-header">
    @lang('auth.order') #{{ $order->getId() }}
  </div>
  <div class="card-body">
    <b>@lang('auth.date'):</b> {{ $order->getCreatedAt() }}<br />
    <b>@lang('auth.total'):</b> ${{ $order->getTotal() }}<br />
    <table class="table table-bordered table-striped text-center mt-3">
      <thead>
        <tr>
          <th scope="col">@lang('auth.item_id')</th>
          <th scope="col">@lang('auth.product_name')</th>
          <th scope="col">@lang('auth.price')</th>
          <th scope="col">@lang('auth.quantity')</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->getItems() as $item)
        <tr>
          <td>{{ $item->getId() }}</td>
          <td>
            <a class="link-success" href="{{ route('product.show', ['id'=> $item->getProduct()->getId()]) }}">
              {{ $item->getProduct()->getName() }}
            </a>
          </td>
          <td>${{ $item->getPrice() }}</td>
          <td>{{ $item->getQuantity() }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@empty
<div class="alert alert-danger" role="alert">
  @lang('auth.no_orders_message')
</div>
@endforelse
@endsection
