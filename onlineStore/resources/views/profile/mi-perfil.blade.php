@extends('layouts.app')

@section('title', __('auth.my_profile'))

@section('content')
<div class="container py-5">
    <h2 class="mb-4">@lang('auth.my_profile')</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <p><strong>@lang('auth.name'):</strong> {{ $user->name }}</p>
            <p><strong>@lang('auth.email'):</strong> {{ $user->email }}</p>
            <p><strong>@lang('auth.role'):</strong> {{ ucfirst($user->role) }}</p>
            <p class="fs-4"><strong>@lang('auth.balance'):</strong> ${{ number_format($user->balance, 2) }}</p>
            
            <form action="{{ route('user.addBalance') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> @lang('auth.add_balance')
                </button>
            </form>
        </div>
    </div>
</div>
@endsection