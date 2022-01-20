<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ url('/') }}">Erke Book Store</a>
    <button class="order-1 btn btn-link btn-sm order-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
  
    <!-- Navbar-->
    <ul class="navbar-nav ml-auto">
        <div class="dropdown">
            <a class="nav-link dropdown-toggle" 
                id="userDropdown" 
                href="#!" 
                role="button" 
                data-toggle="dropdown" 
                aria-haspopup="true" 
                aria-expanded="false">
                
                <i class="fas fa-user fa-fw"></i> Muhammad Salman Agustian
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
            </div>
        </div>

    </ul>
</nav>