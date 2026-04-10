<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link href="{{ asset('/css/admin.css') }}" rel="stylesheet" />
  <title>@yield('title', __('auth.admin_panel'))</title>
  <style>
    /* Estilos generales */
    body {
      background-color: #F4F6F7; 
      color: #34495E;
    }

    /* Navbar */
    .navbar {
      background-color:rgb(29, 42, 54); 
    }

    /* Botones */
    .btn-primary {
      background-color: #1ABC9C;
      color: white;
      border: none;
    }

    .btn-primary:hover {
      background-color: #16A085; 
    }

    /* Sidebar */
    .sidebar {
      background-color: #2C3E50; 
      color: white;
    }

    .sidebar a {
      color: white;
    }

    .sidebar a:hover {
      background-color: #34495E; 
    }

    .language-selector {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 9999;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 5px;
      border: 1px solid #ccc;
    }

    .language-selector select {
      padding: 8px;
      font-size: 14px;
      background-color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .language-selector select:focus {
      outline: none;
      border-color: #1ABC9C;
    }

    /* Footer */
    .copyright {
      background-color:rgb(29, 42, 54);
      color: white;
    }
  </style>
</head>

<body>
  <div class="row g-0">
    <!-- sidebar -->
    <div class="p-3 col fixed text-white sidebar">
      <a href="{{ route('admin.home.index') }}" class="text-white text-decoration-none">
        <span class="fs-4">@lang('auth.admin_panel')</span>
      </a>
      <hr />
      <ul class="nav flex-column">
        <li><a href="{{ route('admin.home.index') }}" class="nav-link text-white">- @lang('auth.admin') - 🏠 @lang('auth.home')</a></li>
        <li><a href="{{ route('admin.product.index') }}" class="nav-link text-white">- @lang('auth.admin') - 📦 @lang('auth.products')</a></li>
        <li><a href="{{ route('admin.orders.index') }}" class="nav-link text-white">- @lang('auth.admin') - 🛍 @lang('auth.orders')</a></li> 
        <li><a href="{{ route('admin.users.index') }}" class="nav-link text-white">- @lang('auth.admin') - 👥 @lang('auth.usersAd')</a></li>
        <li><a href="{{ route('admin.dashboard') }}" class="nav-link text-white">- @lang('auth.admin') - 📊 @lang('auth.dashboard')</a></li> 
        <li>
          <a href="{{ route('home.index') }}" class="mt-2 btn btn-primary">@lang('auth.go_back_home')</a>
        </li>
      </ul>
    </div>
    <!-- sidebar -->
    <div class="col content-grey">
      <nav class="p-3 shadow text-end" style="background-color: #2C3E50;">
        <span class="profile-font text-white">@lang('auth.admin')</span>
      </nav>

      <div class="g-0 m-5">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Select de idioma -->
  <div class="language-selector">
      <select onchange="window.location.href = this.value">
          <option value="{{ route('language.switch', 'en') }}" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
          <option value="{{ route('language.switch', 'es') }}" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Español</option>
      </select>
  </div>

  <!-- footer -->
  <div class="copyright py-4 text-center text-white">
    <div class="container">
        
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>