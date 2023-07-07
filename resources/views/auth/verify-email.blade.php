<x-guest-layout>

    <div class="card card-primary">

        <div class="card-body">
            <div class="app-brand justify-content-center">
                <img src="{{ url('assets/img/hilab.ico') }}" alt="logo" width="50" alt="">
                <a href="{{ route('login') }}" class="app-brand-link gap-2">
                    <span class="app-brand-text demo text-body fw-bolder">lab Information System</span>
                </a>
            </div>
            <p class="mb-4">Please verification to your account and check your mail</p>
            <div class="buttons row">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf


                    <button class="btn btn-primary btn-lg btn-block" style="width: 100%">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="btn btn-danger btn-lg btn-block mt-3" style="width: 100%">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>


        </div>
    </div>
    </section>
</x-guest-layout>
