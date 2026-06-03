@extends('layouts.auth')

@section('title', __('Login'))

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-6">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                
                                <span class="app-brand-text demo text-heading fw-bold">{{ config('app.name') }}</span>
                            </a>
                        </div>

                        <h4 class="mb-1">{{ __('Welcome back!') }} 👋</h4>
                        <p class="mb-6">{{ __('Please sign in to your account to continue') }}</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-4" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-6">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="{{ __('Enter your email') }}" required
                                    autofocus autocomplete="username" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <div class="input-group input-group-merge @error('password') has-validation @enderror">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="············" required autocomplete="current-password"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer" id="toggle-password"
                                        role="button" tabindex="0" aria-label="{{ __('Show password') }}">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="mb-6">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="remember_me"
                                            name="remember" {{ old('remember') ? 'checked' : '' }} />
                                        <label class="form-check-label" for="remember_me">
                                            {{ __('Remember me') }}
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            <span>{{ __('Forgot password?') }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div> --}}

                            <button class="btn btn-primary d-grid w-100" type="submit">
                                {{ __('Log in') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function() {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('bx-hide', !isHidden);
            icon.classList.toggle('bx-show', isHidden);
        });
    </script>
@endpush
