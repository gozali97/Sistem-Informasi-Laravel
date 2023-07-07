<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <div class="app-brand justify-content-center">
                <img src="{{ url('assets/img/hilab.ico') }}" alt="logo" width="50" alt="">
                <a href="{{ route('login') }}" class="app-brand-link gap-2">
                    <span class="app-brand-text demo text-body fw-bolder">lab Information System</span>
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Welcome to LIS! 👋</h4>
            <p class="mb-4">Please sign-in to your account and start the adventure</p>

            <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-6">
                        <label for="frist_name">First Name</label>
                        <input id="frist_name" type="text" value="{{ old('first_name') }}" class="form-control"
                            name="first_name" autofocus>
                    </div>
                    <div class="form-group col-6">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" type="text" value="{{ old('last_name') }}" class="form-control"
                            name="last_name">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="username">Username</label>
                        <input id="username" type="text" class="form-control" type="text" name="username"
                            value="{{ old('username') }}" required autofocus>
                    </div>
                    <div class="form-group col-6">
                        <label for="role">Roles</label>
                        <select name="tacces_code" class="form-control" id="tacces_code">
                            <option value="">-- pilih --</option>
                            @php
                                $roles = \App\Models\role::all();
                            @endphp
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                        required>
                    <div class="invalid-feedback">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-6">
                        <label for="password" class="d-block">Password</label>
                        <input id="password" type="password" class="form-control pwstrength"
                            data-indicator="pwindicator" name="password">
                        <div id="pwindicator" class="pwindicator">
                            <div class="bar"></div>
                            <div class="label"></div>
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="password2" class="d-block">Password Confirmation</label>
                        <input id="password2" type="password" class="form-control" name="password_confirmation">
                    </div>
                </div>

                <div class="mt-3">
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100">Sign up</button>
            </form>
        </div>
    </div>

</x-guest-layout>
