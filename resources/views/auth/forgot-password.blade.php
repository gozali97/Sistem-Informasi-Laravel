<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
                <img src="{{ url('assets/img/hilab.ico') }}" alt="logo" width="50" alt="">
                <a href="index.html" class="app-brand-link gap-2">
                    <span class="app-brand-text demo text-body fw-bolder">Lab Information System</span>
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Forgot Password? 🔒</h4>
            <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
            <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" value="{{ old('email') }}" name="email"
                        placeholder="Enter your email" autofocus />
                </div>
                <button class="btn btn-primary d-grid w-100">Send Reset Link</button>
            </form>
            <div class="text-center">
                <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                    <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                    Back to login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
