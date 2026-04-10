@extends('layouts.app')
@section('title', $viewData["title"])
@section('content')
<div class="row">
  <div class="col-md-6 col-lg-4 mb-2">
    <img src="{{ asset('/img/game.png') }}" class="img-fluid rounded">
  </div>
  <div class="col-md-6 col-lg-4 mb-2">
    <img src="{{ asset('/img/safe.png') }}" class="img-fluid rounded">
  </div>
  <div class="col-md-6 col-lg-4 mb-2">
    <img src="{{ asset('/img/submarine.png') }}" class="img-fluid rounded">
  </div>
</div>

  <!-- Body -->
  <div class="container my-5">
  <div class="row mb-4">
      <div class="col-12 text-center">
        <h2 class="display-4" style="color: #FFA07A;">@lang('auth.welcome')</h2>
        <p class="lead text-muted">@lang('auth.explore_products')</p>
      </div>
  </div>
  
@endsection
