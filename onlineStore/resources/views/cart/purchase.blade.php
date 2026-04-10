@extends('layouts.app')

@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])

@section('content')
<div class="card">
  <div class="card-header">
    @lang('auth.purchase_completed')
  </div>
  <div class="card-body">
    <div class="alert alert-success" role="alert">
      @lang('auth.congratulations_purchase', ['order_id' => $viewData["order"]->getId()])
    </div>
  </div>
</div>
@endsection
