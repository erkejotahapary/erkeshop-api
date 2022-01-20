<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-brand">
            <div class="logo--brand">
                <img src="{{ asset('assets/images/logo-navbar.png') }}" alt="">
            </div>
            <div class="title--brand">
                E-Library
            </div>
            <div class="toggle--brand" id="sidebarToggle" role="button">
                <i class="fas fa-bars"></i>
            </div>
        </div>
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading"></div>
                {{-- <a class="nav-link" href="#">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a> --}}
                {{-- <div class="sb-sidenav-menu-heading">Master</div> --}}
                <a class="nav-link" href="{{ route('user.index') }}" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    User
                </a>
                <a class="nav-link" href="{{ route('book.index') }}" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-cubes"></i></div>
                    Produk Buku
                </a>
            </div>
        </div>
    </nav>
</div>