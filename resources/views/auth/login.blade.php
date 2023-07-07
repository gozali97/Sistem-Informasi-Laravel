<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
                <img src="{{ url('assets/img/hilab.ico') }}" alt="logo" width="50" alt="">
                <a href="{{ route('login') }}" class="app-brand-link gap-2">
                    <span class="app-brand-text demo text-body fw-bolder">lab Information System</span>
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Welcome to LIS! 👋</h4>
            <p class="mb-4">Please sign-in to your account and start the adventure</p>

            <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Enter your username" autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <small>Forgot Password?</small>
                            </a>
                        @endif
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
            </form>

            <p class="text-center">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                    <span>Create an account</span>
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>

<script>
    @if (session('toast_success'))
        iziToast.success({
            title: 'Success',
            message: '{{ session('toast_success') }}',
            position: 'topRight',
            timeout: 3000,
            transitionIn: 'fadeIn',
            transitionOut: 'fadeOut'
        });
    @endif
</script>
