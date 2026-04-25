<div id="sidebar" style="position: fixed; top: 0; left: 0; width: 250px; height: 100vh; background: white; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; transition: transform 0.3s ease; z-index: 1000;">
    
    <!-- Logo Section -->
      <!-- Logo Section -->
   <div class="d-flex align-items-start justify-content-start  border-bottom" style="background-color: #ffffff;">
    <div class="d-flex align-items-center">
        <img src="{{ asset('assets/img/logo.svg') }}" 
             alt="Logo" 
             style="width: 75px; height: 75px; object-fit: contain;" 
             >
        
        <div>
            <div style="font-size: 1.1rem; font-weight: 700; color: black; line-height: 1; letter-spacing: -0.5px;">
                Exam Manager
            </div>
           
        </div>
    </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav style="flex: 1; overflow-y: auto; padding: 16px 0;">
        <ul style="list-style: none; margin: 0; padding: 0;">
            <li>
                <a href="{{ route('owner.dashboard') }}"  class="{{ request()->routeIs(['owner.dashboard']) ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #6b7280; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                    <i class="fas fa-th-large" style="width: 20px; font-size: 16px;"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('exam') }}"  class=" {{ request()->routeIs(['exam','exam.*']) ? 'active':''  }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #6b7280; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                    <i class="fas fa-file-circle-plus" style="width: 20px; font-size: 16px;"></i>
                    <span>Exam</span>
                </a>
            </li>
           
           
            

           

           


            
            
           
           
            
        </ul>
        
        <!-- Settings (separated) -->
        <ul style="list-style: none;  padding: 1px; ">
            <li>
                <a href="{{route('owner.dashboard.setting')}}" class="{{ request()->routeIs(['owner.dashboard.setting']) ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #6b7280; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                    <i class="fas fa-cog" style="width: 20px; font-size: 16px;"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- User Profile Section -->
    <div style="padding: 16px 20px; border-top: 1px solid #e5e7eb; position: relative;">
        <div id="profileButton" style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 8px; border-radius: 8px; transition: background 0.2s;" onmouseenter="this.style.background='#f9fafb'" onmouseleave="this.style.background='transparent'">
            <div style="width: 40px; height: 40px; background-color: #2972da; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
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
            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 13px; font-weight: 600; color: #1f2937; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ auth()->user()->name }}
                </div>
                <div style="font-size: 11px; color: #6b7280; line-height: 1.3;">{{ auth()->user()->email }}</div>
                 <span
                class="badge rounded-pill border border-dark text-uppercase fw-bold text-dark"
                style="font-size: 9.5px; padding: 4px 10px; letter-spacing: 0.5px;"
            > 

            {{ auth()->user()->role }}
            <!-- if user has active susbcription display the subscription name-->
           {{-- {{ $activeSubscription?->subscriptionplan?->plan_name ?? 'Free Plan' }} --}}
            </span>
            </div>
          
        </div>
        
        <!-- Dropdown Menu -->
        <div id="profileDropdown" style="position: absolute; bottom: 100%; left: 16px; right: 16px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); margin-bottom: 8px; display: none; z-index: 1100;">
            <div style="padding: 8px 0;">
                {{-- <a href="#profile" style="display: flex; align-items: center; gap: 12px; padding: 10px 16px; color: #1f2937; text-decoration: none; font-size: 14px; transition: background 0.2s;" onmouseenter="this.style.background='#f9fafb'" onmouseleave="this.style.background='transparent'">
                    <i class="fas fa-user" style="width: 16px; color: #6b7280;"></i>
                    <span>Profile</span>
                </a>
                <a href="#account-settings" style="display: flex; align-items: center; gap: 12px; padding: 10px 16px; color: #1f2937; text-decoration: none; font-size: 14px; transition: background 0.2s;" onmouseenter="this.style.background='#f9fafb'" onmouseleave="this.style.background='transparent'">
                    <i class="fas fa-cog" style="width: 16px; color: #6b7280;"></i>
                    <span>Account Settings</span>
                </a> --}}
               <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    style="
                        border: none;
                        background-color: transparent !important;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 10px 16px;
                        color: #ef4444;
                        font-size: 14px;
                        cursor: pointer;
                        transition: background 0.2s;"
                >
                    <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                    <span>Logout</span>
                </button>
            </form>

            </div>
        </div>
    </div>
</div>

<!-- Mobile Toggle Button -->
<button id="sidebarToggle" style="position: fixed; top: 10px; right: 16px; z-index: 1001; background: white; border: 1px solid #e5e7eb; border-radius: 8px; width: 40px; height: 40px; display: none; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <i class="fas fa-bars" style="color: #1f2937;"></i>
</button>

<!-- Overlay for mobile -->
<div id="overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; display: none;"></div>
