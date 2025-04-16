<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Sidebar Menu | Side Navigation Bar</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <!-- Boxicons CSS -->
    <link
      href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>
  <body>
    <nav>
      <div class="logo">
        <i class="bx bx-menu menu-icon"></i>
        <span class="logo-name text-end fst-italic fw-bold">AvoBank</span>
      </div>
      <div class="sidebar">
        <div class="sidebar-content">
          <ul class="lists">
            @if (Auth::user()->role->name == 'student')
            <li class="list">
              <a href="{{route('wallet')}}" class="nav-link">
                <i class="bx bx-home-alt icon"></i>
                <span class="link">Wallet</span>
              </a>
            </li>
            @endif
            @if (Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'bank')
            <li class="list">
              <a href="{{route('pocket')}}" class="nav-link">
                <i class="bx bx-bar-chart-alt-2 icon"></i>
                <span class="link">Pocket</span>
              </a>
            </li>
            @endif
            @if (Auth::user()->role->name == 'admin' || Auth::user()->role->name == 'bank')
            <li class="list">
              <a href="{{route('register')}}" class="nav-link">
                <i class="bx bx-bell icon"></i>
                <span class="link">register</span>
              </a>
            </li>
            @endif
          </ul>
          <div class="bottom-content">
            <li class="list">
              <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link" style="background: none; border: none; padding: 0; display: flex; align-items: center; cursor: pointer;">
                  <i class="bx bx-log-out icon"></i>
                  <span class="link">Logout</span>
                </button>
              </form>
            </li>
          </div>          
        </div>
      </div>
    </nav>
    <section class="overlay"></section>
    <script src="script.js"></script>
  </body>
</html>