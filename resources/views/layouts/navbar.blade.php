<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <p href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media justify-content-center">
                        <img src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mb-3 img-circle">
                    </div>
                    <!-- Message End -->
                </p>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">
                    <b>{{ ucwords(strtolower(Auth::user()->firstname)) }} {{ ucwords(strtolower(Auth::user()->lastname)) }}</b>
                </a>
                <div class="dropdown-divider"></div>

                @if (Auth::user()->hasVerifiedEmail())
                    <p class="text-success dropdown-item dropdown-footer">
                        <strong>Your Email Address is Verified</strong>
                    </p>
                @else
                    <a href="{{ route('verification.notice') }}" class="text-danger dropdown-item dropdown-footer">
                        <b>Verify Your Email Address</b>
                    </a>
                @endif
                <div class="dropdown-divider"></div>

                <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">Log Out</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->