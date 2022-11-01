<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
{{--       <li class="nav-item">--}}
{{--        <div class="dropdown show">--}}
{{--          <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--            @if(Session::has('locale') && Session::get('locale') == 'ar') Arabic @else English @endif--}}
{{--          </a>--}}
{{--          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">--}}
{{--            <a class="dropdown-item" href="{{route('changeLanguage',['locale' => 'en'])}}">English</a>--}}
{{--            <a class="dropdown-item" href="{{route('changeLanguage',['locale' => 'ar'])}}">Arabic</a>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </li>--}}
    </ul>

    {{-- <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> --}}

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-primary navbar-badge" id="unread-badge" style="display: none;"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification-container">

        </div>
      </li>
      <li class="nav-item">
        @if(Auth::guard('admin')->check())
        <a class="nav-link" href="{{ route('admin.logout') }}">
          {{ __('Logout') }}
        </a>
        @else
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" role="button">
          {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @endif
      </li>
    </ul>
  </nav>
  <!-- /.navbar
