@extends('layouts.app')

@section('title', __('auth.about_us_title'))
@section('subtitle', __('auth.about_us_subtitle'))

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h2 class="fw-bold">@lang('auth.about_us_who')</h2>
            <p class="text-muted">@lang('auth.about_us_description')</p>
            <h3 class="fw-bold mt-4">@lang('auth.our_mission')</h3>
            <p class="text-muted">@lang('auth.our_mission_text')</p>
            <h3 class="fw-bold mt-4">@lang('auth.why_choose_us')</h3>
            <ul class="text-muted">
                <li>@lang('auth.quality_products')</li>
                <li>@lang('auth.fast_shipping')</li>
                <li>@lang('auth.secure_payments')</li>
                <li>@lang('auth.excellent_support')</li>
            </ul>
        </div>
        <div class="col-md-6">
            <img src="{{ asset('img/about-us.jpg') }}" class="img-fluid rounded shadow" alt="Sobre Nosotros">
        </div>
    </div>
</div>
@endsection
