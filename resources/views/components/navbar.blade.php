<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-0 border-bottom" id="mainNavbar">
  <div class="container">
    <!-- Brand / Logo -->
  <div class="navbar-brand d-flex align-items-center">
    <img src="{{ asset('assets/img/logo.svg') }}" 
         alt="Logo" 
         class='me-2'
        {{-- style="height:52px; width:auto;" --}}
         style="height:56px; transform: scale(1.2); transform-origin: left center;"
        >
    <span class="fw-semibold text-dark" style="font-size:16px;">
        Exam Manager
    </span>
</div>

    <!-- Toggler for mobile -->
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarContent"
      aria-controls="navbarContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <i class="fas fa-bars"></i>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
      <!-- Center nav links -->
      <ul class="navbar-nav mb-2 mb-lg-0 mx-auto ">
        <li class="nav-item">
          <a class="nav-link px-1 {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item px-1">
          <a class="nav-link #">Features</a>
        </li>
       
      @guest
      <li class="nav-item px-1">
        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }} " href="{{ route('login') }}">Login</a>
      </li>
      @endguest
      </ul>


      <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-2 gap-lg-3 mt-2 mt-lg-0 mb-2 mb-lg-0" id="right-section">


      @guest
      <a href="{{ route('register') }}" class="btn  text-white text-capitalize fw-bold  shadow-0 "
       onmouseover="this.style.backgroundColor='#2f588d'; this.style.color='white';"
        onmouseout="this.style.backgroundColor='#226ac9'; this.style.color='white';"
       style='background-color:#226ac9; transition: background-color 0.3s; font-size:14px;'>
          Create Account
      </a>
      @endguest

      @auth

      
     <!-- User Profile Section -->
<div class="dropdown">
    <button
        id="profileButton"
        class="btn btn-light w-100 d-flex align-items-start p-2 rounded-3 border text-start"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        style="cursor: pointer;"
    >
        <!-- Avatar -->
        <div
            class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-0"
            style="width: 40px; height: 40px; background-color: #2972da; font-size: 14px;"
        >
        @php
        $name = trim(auth()->user()->name ?? '');
        $parts = preg_split('/\s+/', $name);

        if (count($parts) > 1) {
            $initials = strtoupper(
                substr($parts[0], 0, 1) . substr($parts[1], 0, 1)
            );
        } else {
            $half = ceil(strlen($name) / 2);

            $first = substr($name, 0, 1);
            $second = substr($name, $half, 1);

            $initials = strtoupper($first . ($second ?: ''));
        }
        @endphp

        {{ $initials }}
        </div>

        <!-- Profile info -->
        <div class="ms-3 overflow-hidden grow">
            <div
                class="fw-bold text-dark text-truncate mb-1 text-capitalize"
                title="{{ auth()->user()->name }}"
                style="font-size: 14px; line-height: 1.2;"
            >
                {{ auth()->user()->name }}
            </div>

            <span
                class="badge rounded-pill border border-dark text-uppercase fw-bold text-dark"
                style="font-size: 9px; padding: 4px 10px; letter-spacing: 0.5px;"
            > 

            {{ auth()->user()->role }}
            <!-- if user has active susbcription display the subscription name-->
           {{-- {{ $activeSubscription?->subscriptionplan?->plan_name ?? 'Free Plan' }} --}}
            </span>
        </div>
    </button>

    <!-- Dropdown -->
    <ul class="dropdown-menu shadow border-0 p-2 w-100">
        <li>
            <a 
            {{-- owner link of dashboard --}}
            @if (auth()->user()->role == 'owner')
            href="{{ route('owner.dashboard') }}"
            @endif
            {{-- editor link of the dashboard --}}
            @if (auth()->user()->role == 'editor')
            href="{{ route('editor.dashboard') }}"
            @endif

               class="dropdown-item rounded-2 d-flex align-items-center gap-3 py-2">
                <i class="fas fa-layer-group text-muted" style="width: 16px;"></i>
                Dashboard
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>
        <li>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button
                    type="submit"
                    class="dropdown-item rounded-2 d-flex align-items-center gap-3 py-2 text-danger border-0 bg-transparent w-100 text-start"
                >
                    <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                    Logout
                </button>
            </form>
        </li>
    </ul>
</div>

      @endif

</div>

    </div>
  </div>
</nav>

