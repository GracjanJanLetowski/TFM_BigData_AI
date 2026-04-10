@extends('layouts.admin')

@section('title', __('auth.admin_orders'))
@section('subtitle', __('auth.order_list'))

@section('content')
<div class="container">
    <h2>@lang('auth.order_list')</h2>

    <!-- Tabla de pedidos -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>@lang('auth.order_id')</th>
                <th>@lang('auth.user')</th>
                <th>@lang('auth.total')</th>
                <th>@lang('auth.date')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td> 
                <td>${{ number_format($order->total, 2) }}</td> 
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td> 
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
</div>
@endsection