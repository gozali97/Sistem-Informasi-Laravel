<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <h4 class="mt-2">Lab Information System</h4>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Place this tag where you want the button to render. -->
            <li class="nav-item lh-1 me-3 dropdown">
                <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-bell"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                    <a class="dropdown-item" href="javascript:void(0);"><small>Message from jhon</small></a>
                    <a class="dropdown-item" href="javascript:void(0);"><small>Message from laura</small></a>
                </div>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if (Auth::user()->foto)
                            <img src="{{ url('/assets/img/profile/', Auth::user()->foto) }}" alt
                                class="w-px-40 h-auto rounded-circle" />
                        @else
                            <img src="{{ url('/assets/img/profile/user-default.png') }}" alt
                                class="w-px-40 h-auto rounded-circle" />
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if (Auth::user()->foto)
                                            <img src="{{ url('/assets/img/profile/', Auth::user()->foto) }}" alt
                                                class="w-px-40 h-auto rounded-circle" />
                                        @else
                                            <img src="{{ url('/assets/img/profile/user-default.png') }}" alt
                                                class="w-px-40 h-auto rounded-circle" />
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->username }}</span>
                                    <small class="text-muted">{{ Auth::user()->first_name }}</small>
                                    <small class="text-muted text xs" id="loginTime">Login <span
                                            id="loginDuration"></span>
                                        ago</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="display: flex; align-items: center;">
                                <i class="bx bx-power-off me-2" style="margin-right: 5px;"></i>
                                Logout
                            </button>

                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
<script>
    function calculateLoginTime() {
        var now = new Date();
        var loginTime = new Date('{{ Auth::user()->last_login }}');

        var diffInMilliseconds = now - loginTime;
        var diffInMinutes = Math.floor(diffInMilliseconds / 1000 / 60);

        document.getElementById("loginTime").textContent = "Login " + diffInMinutes + " min ago";
    }

    calculateLoginTime();

    setInterval(calculateLoginTime, 60000);
</script>
