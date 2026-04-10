<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />
  <title>@yield('title', __('auth.online_store'))</title>
  <style>
    body {
      background-color: #f7f7f7; 
    }

    .navbar {
      background-color: #ff6347; 
    }

    .navbar .navbar-brand, .navbar .nav-link {
      color: #fff; 
    }

    .navbar .nav-link:hover {
      color: #ffd700; 
    }

    .masthead {
      background-color: #000000; 
      color: #fff;
    }

    .masthead h2 {
      color: #fff; 
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




  </style>
</head>
<body>
  <!-- header -->
  <nav class="navbar navbar-expand-lg navbar-dark py-4">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home.index') }}">@lang('auth.online_store')</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto">
        <a class="nav-link active" href="{{ route('home.index') }}">@lang('auth.home') 🏠</a>
        <a class="nav-link active" href="{{ route('product.index') }}">@lang('auth.products') 💻</a>
        <a class="nav-link active" href="{{ route('cart.index') }}">@lang('auth.cart') 🛒</a>
        <a class="nav-link active" href="{{ route('about') }}">@lang('auth.about') 👀</a>
        <div class="vr bg-white mx-2 d-none d-lg-block"></div>
          @guest
          <a class="nav-link active" href="{{ route('login') }}">@lang('auth.login') 🔑</a>
          <a class="nav-link active" href="{{ route('register') }}">@lang('auth.register') 📝</a>
          @else
          @if(Auth::user()->getRole() == 'admin')
          <a class="nav-link active" href="{{ route('admin.dashboard') }}">Panel Admin ⚙️</a>
          @endif
          <a class="nav-link active" href="{{ route('user.profile') }}">@lang('auth.my_profile') 👤</a>
          <a class="nav-link active" href="{{ route('myaccount.orders') }}">@lang('auth.my_orders') 🛍️</a>
          <form id="logout" action="{{ route('logout') }}" method="POST">
            <a role="button" class="nav-link active"
            onclick="document.getElementById('logout').submit();">@lang('auth.logout') 🚪</a>
            @csrf
          </form>
          @endguest
        </div>
      </div>
    </div>
  </nav>



  <!-- Contenido principal -->
  <div class="row">
      <div class="col-12">
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

<!-- Footer -->
<footer class="bg-dark text-white py-4">
  <div class="container">
      <div class="row">
          <div class="col-md-4 mb-3">
            <h5>@lang('auth.about_us')</h5>
            <p>@lang('auth.welcome') @lang('auth.explore_products')</p>
          </div>
          <div class="col-md-4 mb-3">
          <h5>@lang('auth.quick_links')</h5>
              <ul class="list-unstyled">
                <li><a href="{{ route('product.index') }}" class="text-white text-decoration-none">@lang('auth.products')</a></li>
                <li><a href="{{ route('cart.index') }}" class="text-white text-decoration-none">@lang('auth.cart')</a></li>
                <li><a href="#" class="text-white text-decoration-none">@lang('auth.contact')</a></li>
              </ul>
          </div>
          <div class="col-md-4 mb-3">
              <h5>@lang('auth.social_media')</h5>
              <ul class="list-unstyled d-flex">
                  <li><a href="#" class="text-white mx-2"></a>Facebook</li>
                  <li><a href="#" class="text-white mx-2"></a>Twitter</li>
                  <li><a href="#" class="text-white mx-2"></a>Instagram</li>
              </ul>
          </div>
      </div>
      <div class="text-center mt-4">
        <p class="m-0">&copy; {{ date('Y') }} @lang('auth.online_store'). @lang('auth.rights_reserved')</p>
      </div>
  </div>
</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
