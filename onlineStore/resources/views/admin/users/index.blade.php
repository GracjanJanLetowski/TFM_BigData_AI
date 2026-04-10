@extends('layouts.admin')

@section('title', __('auth.tituloCrudUsuarios'))

@section('content')
<div class="container py-5">
    <h2 class="mb-4">@lang('auth.users')</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-3">➕ @lang('auth.create_user')</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>@lang('auth.id')</th>
                <th>@lang('auth.name')</th>
                <th>@lang('auth.email')</th>
                <th>@lang('auth.role')</th>
                <th>@lang('auth.balance')</th>
                <th>@lang('auth.actions')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>${{ number_format($user->balance, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">✏️ @lang('auth.edit')</a>
                        
                        <form action="{{ route('admin.users.addBalance', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">💰 +1000€</button>
                        </form>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('@lang('auth.confirm_delete')');">🗑 @lang('auth.delete')</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
