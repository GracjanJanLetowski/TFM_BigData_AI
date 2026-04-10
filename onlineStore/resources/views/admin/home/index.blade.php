@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')
<div class="card">
  <div class="card-header">
    @lang('auth.admin_panel_home')
  </div>
  <div class="card-body">
    @lang('auth.admin_panel_welcome')
  </div>
</div>
@endsection