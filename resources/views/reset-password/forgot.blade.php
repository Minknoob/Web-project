
@extends('layouts.app')

@section('title', 'Forgot Password')

@section('navbar')
    <a class="btn btn-gc-outline rounded-pill px-3 px-md-4 py-2 fw-semibold"
        href="{{ route('register') }}">
        Register
    </a>

    <a class="btn btn-gc-primary rounded-pill px-3 px-md-4 py-2 fw-semibold"
        href="{{ route('login') }}">
        Login
    </a>
@endsection

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center px-3 py-5">
    <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">

        <div class="card bg-dark border border-secondary rounded-4 shadow-none"
             data-bs-theme="dark">

            <div class="card-body p-4 p-md-5">

                {{-- Header --}}
                <div class="text-center mb-4">

                    <h2 class="fw-bold text-light mb-2">Reset Password</h2>
                    <p class="text-secondary mb-0">
                        Enter your email address and we'll send you a reset link.
                    </p>
                </div>

                {{-- Success Message --}}
                @if (session('status'))
                    <div class="alert alert-success bg-black border border-success text-success small rounded-3"
                         role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Reset Password Form --}}
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="email"
                               class="form-label text-light fw-semibold small">
                            Email Address
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-black border-secondary text-secondary">
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="5" width="18" height="14" rx="2"
                                          stroke="currentColor" stroke-width="1.7"/>
                                    <path d="m4 7 8 6 8-6"
                                          stroke="currentColor" stroke-width="1.7"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>

                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control bg-black text-light border-secondary py-2"
                                   placeholder="Enter your email"
                                   value="{{ old('email') }}"
                                   autocomplete="email">
                        </div>

                        {{-- Validation Error --}}
                        @if ($errors->any())
                            <div class="text-danger small mt-2">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    

                    <button type="submit"
                            class="btn btn-gc-primary w-100 rounded-3 py-2 fw-bold">
                        Send Reset Link
                    </button>
                </form>

                {{-- Back to Login --}}
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}"
                       class="text-link-decor small fw-semibold">
                        ← Back to Login
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
